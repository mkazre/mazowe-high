import { Tabs } from 'expo-router';
import React from 'react';
import { colors } from '../../src/theme/tokens';

export default function TeacherLayout() {
  return (
    <Tabs screenOptions={{ headerShown: false, tabBarActiveTintColor: colors.red, tabBarInactiveTintColor: colors.muted }}>
      <Tabs.Screen name="index" options={{ title: 'Home' }} />
      <Tabs.Screen name="register" options={{ title: 'Register' }} />
      <Tabs.Screen name="marks" options={{ title: 'Marks' }} />
      <Tabs.Screen name="classes" options={{ title: 'Classes' }} />
      <Tabs.Screen name="homework" options={{ href: null }} />
      <Tabs.Screen name="conduct" options={{ href: null }} />
    </Tabs>
  );
}
