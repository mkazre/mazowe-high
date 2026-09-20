import React from 'react';
import { Loading } from '../src/components/ui';

// Expo Router needs a real screen matching "/" or a fresh install opening at
// the app's own scheme URL (e.g. mazoweheights:///) shows "Unmatched Route"
// before _layout.tsx's RoleGuard ever gets a chance to redirect. The actual
// destination (onboarding vs login vs role home) is decided there.
export default function RootIndex() {
  return <Loading />;
}
