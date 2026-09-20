import { Ionicons } from '@expo/vector-icons';
import { Tabs } from 'expo-router';
import React from 'react';
import AppHeaderLogo from '../../src/components/AppHeaderLogo';
import { colors } from '../../src/theme/tokens';

export default function ParentLayout() {
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
      <Tabs.Screen name="fees" options={{ title: 'Fees', tabBarIcon: ({ color, size }) => <Ionicons name="cash-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="menu" options={{ title: 'Menu', tabBarIcon: ({ color, size }) => <Ionicons name="restaurant-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="messages" options={{ title: 'Messages', tabBarIcon: ({ color, size }) => <Ionicons name="chatbubble-ellipses-outline" size={size} color={color} /> }} />
      <Tabs.Screen name="events" options={{ href: null }} />
      <Tabs.Screen name="bus" options={{ href: null }} />
      <Tabs.Screen name="exeat" options={{ href: null }} />
      <Tabs.Screen name="children/[id]" options={{ href: null }} />
    </Tabs>
  );
}
