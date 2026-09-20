import { Tabs } from 'expo-router';
import React from 'react';
import { colors } from '../../src/theme/tokens';

export default function ParentLayout() {
  return (
    <Tabs screenOptions={{ headerShown: false, tabBarActiveTintColor: colors.red, tabBarInactiveTintColor: colors.muted }}>
      <Tabs.Screen name="index" options={{ title: 'Home' }} />
      <Tabs.Screen name="fees" options={{ title: 'Fees' }} />
      <Tabs.Screen name="menu" options={{ title: 'Menu' }} />
      <Tabs.Screen name="messages" options={{ title: 'Messages' }} />
      <Tabs.Screen name="events" options={{ href: null }} />
      <Tabs.Screen name="bus" options={{ href: null }} />
      <Tabs.Screen name="exeat" options={{ href: null }} />
      <Tabs.Screen name="children/[id]" options={{ href: null }} />
    </Tabs>
  );
}
