import { useQuery } from '@tanstack/react-query';
import { useRouter } from 'expo-router';
import React from 'react';
import { Text, View } from 'react-native';
import { fetchEvents, fetchNotices } from '../../src/api/content';
import { useAuth } from '../../src/api/auth';
import {
  Button, Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function ParentHome() {
  const { user, logout } = useAuth();
  const router = useRouter();
  const notices = useQuery({ queryKey: ['notices'], queryFn: fetchNotices });
  const events = useQuery({ queryKey: ['events'], queryFn: fetchEvents });

  return (
    <Screen>
      <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'flex-start' }}>
        <Header kicker={`Welcome, ${user?.name ?? 'Parent'}`} title="Mazowe Heights" />
        <Button label="Log out" variant="outline" onPress={logout} />
      </View>

      <Text style={{ fontWeight: '800', fontSize: 13, textTransform: 'uppercase', letterSpacing: 1, color: colors.blue, marginBottom: spacing.sm }}>
        My children
      </Text>
      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.md }}>
        {user?.children?.map((c) => (
          <Button
            key={c.id}
            label={`${c.first_name} ${c.last_name}`}
            variant="secondary"
            onPress={() => router.push(`/parent/children/${c.id}`)}
          />
        ))}
        {!user?.children?.length && <Text style={{ color: colors.muted }}>No enrolled children linked to this account.</Text>}
      </View>

      <Text style={{ fontWeight: '800', fontSize: 13, textTransform: 'uppercase', letterSpacing: 1, color: colors.blue, marginBottom: spacing.sm }}>
        Quick links
      </Text>
      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.lg }}>
        <Button label="Bus tracking" variant="secondary" onPress={() => router.push('/parent/bus')} />
        <Button label="Exeat requests" variant="secondary" onPress={() => router.push('/parent/exeat')} />
        <Button label="Events" variant="secondary" onPress={() => router.push('/parent/events')} />
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

      <Text style={{
        fontWeight: '800', fontSize: 13, textTransform: 'uppercase', letterSpacing: 1, color: colors.blue, marginTop: spacing.lg, marginBottom: spacing.sm,
      }}
      >
        What&apos;s on
      </Text>
      {events.isLoading && <Loading />}
      {events.data?.map((e) => (
        <Card key={e.id}>
          <Text style={{ fontWeight: '800' }}>{e.title}</Text>
          <Text style={{ color: colors.muted }}>{e.location}</Text>
        </Card>
      ))}
    </Screen>
  );
}
