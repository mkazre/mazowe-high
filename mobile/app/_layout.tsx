import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { Redirect, Slot, usePathname } from 'expo-router';
import React, { useEffect, useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { onboardingStore } from '../src/api/client';
import { AuthProvider, useAuth } from '../src/api/auth';
import { Loading } from '../src/components/ui';

const queryClient = new QueryClient();

function RoleGuard() {
  const { user, loading } = useAuth();
  const pathname = usePathname();
  const [onboardingSeen, setOnboardingSeen] = useState<boolean | null>(null);

  useEffect(() => {
    onboardingStore.hasSeen().then(setOnboardingSeen);
  }, []);

  if (loading || onboardingSeen === null) return <Loading />;

  const inOnboarding = pathname.startsWith('/onboarding');
  if (!onboardingSeen && !inOnboarding) {
    return <Redirect href="/onboarding" />;
  }
  if (onboardingSeen && inOnboarding) {
    return <Redirect href={user ? roleHome(user.role) : '/login'} />;
  }
  if (inOnboarding) return <Slot />;

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
