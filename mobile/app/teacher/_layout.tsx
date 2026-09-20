import { Ionicons } from '@expo/vector-icons';
import { Tabs } from 'expo-router';
import React from 'react';
import AppHeaderLogo from '../../src/components/AppHeaderLogo';
import { colors } from '../../src/theme/tokens';

export default function TeacherLayout() {
  return (
    <Tabs
      screenOptions={{
        headerShown: true,
        headerTitleAlign: 'center',
        headerTitle: () => <AppHeaderLogo />,
        headerStyle: { backgroundColor: colors.paper },
        headerShadowVisible: true,
        tabBarActiveTintColor: colors.red,
        tabBarInactiveTintColor: colors.muted,
      }}
    >
      <Tabs.Screen name="index" options={{ title: 'Home', tabBarIcon: ({ color, size }) => <Ionicons name="home" size={size} color={color} /> }} />
      <Tabs.Screen name="register" options={{ title: 'Register', tabBarIcon: ({ color, size }) => <Ionicons name="checkbox-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="marks" options={{ title: 'Marks', tabBarIcon: ({ color, size }) => <Ionicons name="create-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="classes" options={{ title: 'Classes', tabBarIcon: ({ color, size }) => <Ionicons name="people-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="homework" options={{ href: null }} />
      <Tabs.Screen name="conduct" options={{ href: null }} />
    </Tabs>
  );
}
