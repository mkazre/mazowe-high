import axios from 'axios';
import * as SecureStore from 'expo-secure-store';

// Point this at the CodeIgniter backend. For a device/simulator on the same
// Wi-Fi as your dev machine, replace localhost with your machine's LAN IP.
export const API_BASE_URL = process.env.EXPO_PUBLIC_API_URL ?? 'http://localhost:8080/api/v1';

const ACCESS_TOKEN_KEY = 'mh_access_token';
const REFRESH_TOKEN_KEY = 'mh_refresh_token';
const ONBOARDING_KEY = 'mh_onboarding_complete';

export const onboardingStore = {
  async hasSeen(): Promise<boolean> {
    return (await SecureStore.getItemAsync(ONBOARDING_KEY)) === '1';
  },
  async markSeen() {
    await SecureStore.setItemAsync(ONBOARDING_KEY, '1');
  },
};

export const tokenStore = {
  async getAccess() {
    return SecureStore.getItemAsync(ACCESS_TOKEN_KEY);
  },
  async getRefresh() {
    return SecureStore.getItemAsync(REFRESH_TOKEN_KEY);
  },
  async set(access: string, refresh?: string) {
    await SecureStore.setItemAsync(ACCESS_TOKEN_KEY, access);
    if (refresh) await SecureStore.setItemAsync(REFRESH_TOKEN_KEY, refresh);
  },
  async clear() {
    await SecureStore.deleteItemAsync(ACCESS_TOKEN_KEY);
    await SecureStore.deleteItemAsync(REFRESH_TOKEN_KEY);
  },
};

export const api = axios.create({ baseURL: API_BASE_URL, timeout: 15000 });

api.interceptors.request.use(async (config) => {
  const token = await tokenStore.getAccess();
  if (token) {
    config.headers = config.headers ?? {};
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

let refreshing: Promise<string | null> | null = null;

api.interceptors.response.use(
  (res) => res,
  async (error) => {
    const original = error.config;
    if (error.response?.status === 401 && !original._retry) {
      original._retry = true;
      if (!refreshing) {
        refreshing = (async () => {
          const refreshToken = await tokenStore.getRefresh();
          if (!refreshToken) return null;
          try {
            const { data } = await axios.post(`${API_BASE_URL}/auth/refresh`, { refresh_token: refreshToken });
            await tokenStore.set(data.access_token);

            return data.access_token as string;
          } catch {
            await tokenStore.clear();

            return null;
          } finally {
            refreshing = null;
          }
        })();
      }
      const newToken = await refreshing;
      if (newToken) {
        original.headers.Authorization = `Bearer ${newToken}`;

        return api.request(original);
      }
    }

    return Promise.reject(error);
  },
);
