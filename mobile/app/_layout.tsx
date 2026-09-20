import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { Redirect, Slot, usePathname } from 'expo-router';
import React from 'react';
import { StatusBar } from 'expo-status-bar';
import { AuthProvider, useAuth } from '../src/api/auth';
import { Loading } from '../src/components/ui';

const queryClient = new QueryClient();

function RoleGuard() {
  const { user, loading } = useAuth();
  const pathname = usePathname();

  if (loading) return <Loading />;

  const inAuthGroup = pathname.startsWith('/login');

  if (!user && !inAuthGroup) {
    return <Redirect href="/login" />;
  }

  if (user && inAuthGroup) {
    return <Redirect href={roleHome(user.role)} />;
  }

  // Once signed in, keep each role inside its own screen group.
  if (user) {
    const home = roleHome(user.role);
    const inOwnGroup = pathname.startsWith(`/${roleSegment(user.role)}`);
    if (!inOwnGroup && !inAuthGroup) {
      return <Redirect href={home} />;
    }
  }

  return <Slot />;
}

function roleSegment(role: string) {
  if (role === 'teacher' || role === 'tutor' || role === 'hod') return 'teacher';
  if (role === 'student') return 'student';

  return 'parent';
}

function roleHome(role: string) {
  return `/${roleSegment(role)}`;
}

export default function RootLayout() {
  return (
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <StatusBar style="dark" />
        <RoleGuard />
      </AuthProvider>
    </QueryClientProvider>
  );
}
