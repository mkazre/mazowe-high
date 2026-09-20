import { Ionicons } from '@expo/vector-icons';
import { Tabs } from 'expo-router';
import React from 'react';
import AppHeaderLogo from '../../src/components/AppHeaderLogo';
import { colors } from '../../src/theme/tokens';

export default function StudentLayout() {
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
      <Tabs.Screen name="timetable" options={{ title: 'Timetable', tabBarIcon: ({ color, size }) => <Ionicons name="calendar-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="grades" options={{ title: 'Grades', tabBarIcon: ({ color, size }) => <Ionicons name="ribbon-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="library" options={{ title: 'Library', tabBarIcon: ({ color, size }) => <Ionicons name="book-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="homework" options={{ href: null }} />
      <Tabs.Screen name="menu" options={{ href: null }} />
      <Tabs.Screen name="clubs" options={{ href: null }} />
    </Tabs>
  );
}
