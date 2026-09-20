import { useQuery } from '@tanstack/react-query';
import { useRouter } from 'expo-router';
import React from 'react';
import { Text, View } from 'react-native';
import { fetchNotices } from '../../src/api/content';
import { useAuth } from '../../src/api/auth';
import { Button, Card, EmptyState, Header, Loading, Screen } from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function StudentHome() {
  const { user, logout } = useAuth();
  const router = useRouter();
  const notices = useQuery({ queryKey: ['notices'], queryFn: fetchNotices });

  return (
    <Screen>
      <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-start' }}>
        <Header kicker="Student portal" title={`Welcome, ${user?.name ?? 'Student'}`} />
        <Button label="Log out" variant="outline" onPress={logout} />
      </View>

      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.lg }}>
        <Button label="Homework" variant="secondary" onPress={() => router.push('/student/homework')} />
        <Button label="This week's menu" variant="secondary" onPress={() => router.push('/student/menu')} />
        <Button label="Clubs & societies" variant="secondary" onPress={() => router.push('/student/clubs')} />
      </View>

      <Text style={{ fontWeight: '800', fontSize: 13, textTransform: 'uppercase', letterSpacing: 1, color: colors.blue, marginBottom: spacing.sm }}>
        Notices
      </Text>
      {notices.isLoading && <Loading />}
      {notices.data?.map((n) => (
        <Card key={n.id}>
          <Text style={{ fontWeight: '800', marginBottom: 4 }}>{n.title}</Text>
          <Text style={{ color: colors.muted }} numberOfLines={3}>{n.body}</Text>
        </Card>
      ))}
      {notices.data?.length === 0 && <EmptyState text="No notices right now." />}
    </Screen>
  );
}
