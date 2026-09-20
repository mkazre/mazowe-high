import React, { createContext, useContext, useEffect, useMemo, useState } from 'react';
import { api, tokenStore } from './client';

export type Role = 'parent' | 'student' | 'teacher' | string;
export type Child = { id: number; first_name: string; last_name: string; admission_number: string; class_id: number };
export type AuthUser = {
  id: number; name: string; email: string; role: Role;
  student_id: number | null; staff_id: number | null; children: Child[];
};

type AuthState = {
  user: AuthUser | null;
  loading: boolean;
  login: (email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
};

const AuthContext = createContext<AuthState | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      const token = await tokenStore.getAccess();
      if (!token) {
        setLoading(false);

        return;
      }
      try {
        const { data } = await api.get('/me');
        setUser(data.user);
      } catch {
        await tokenStore.clear();
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  const login = async (email: string, password: string) => {
    const { data } = await api.post('/auth/login', { email, password });
    await tokenStore.set(data.access_token, data.refresh_token);
    setUser(data.user);
  };

  const logout = async () => {
    await tokenStore.clear();
    setUser(null);
  };

  const value = useMemo(() => ({ user, loading, login, logout }), [user, loading]);

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used within AuthProvider');

  return ctx;
}
