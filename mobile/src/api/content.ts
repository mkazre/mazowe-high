import { api } from './client';

export type Notice = { id: number; title: string; body: string; published_at: string };
export type SchoolEvent = { id: number; title: string; location: string; starts_at: string; tag: string };
export type Post = { id: number; title: string; slug: string; category: string; excerpt: string; published_at: string };

export async function fetchNotices(): Promise<Notice[]> {
  const { data } = await api.get('/notices');

  return data.data;
}

export async function fetchEvents(): Promise<SchoolEvent[]> {
  const { data } = await api.get('/events');

  return data.data;
}

export async function fetchPosts(): Promise<Post[]> {
  const { data } = await api.get('/posts');

  return data.data;
}

export async function submitContact(payload: { name: string; email?: string; phone?: string; subject?: string; message: string }) {
  const { data } = await api.post('/contact', payload);

  return data;
}

export async function submitApplication(payload: Record<string, unknown>) {
  const { data } = await api.post('/applications', payload);

  return data;
}
