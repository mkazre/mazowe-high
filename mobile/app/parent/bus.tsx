import { useQuery } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Text, View } from 'react-native';
import { fetchRouteLive, fetchRoutes } from '../../src/api/portal';
import {
  Button, Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function Bus() {
  const routes = useQuery({ queryKey: ['routes'], queryFn: fetchRoutes });
  const [selected, setSelected] = useState<number | null>(null);
  const live = useQuery({
    queryKey: ['route-live', selected],
    queryFn: () => fetchRouteLive(selected as number),
    enabled: selected !== null,
    refetchInterval: 15000,
  });

  return (
    <Screen>
      <Header kicker="Boarding" title="Bus tracking" />
      {routes.isLoading && <Loading />}
      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.md }}>
        {routes.data?.map((r) => (
          <Button key={r.id} label={r.name} variant={selected === r.id ? 'primary' : 'secondary'} onPress={() => setSelected(r.id)} />
        ))}
      </View>
      {routes.data?.length === 0 && <EmptyState text="No routes configured yet." />}

      {selected !== null && live.data && (
        <Card>
          {live.data.simulated && (
            <Text style={{ fontSize: 11, fontWeight: '800', color: colors.red, textTransform: 'uppercase', marginBottom: 8 }}>
              Simulated location — no live GPS device connected yet
            </Text>
          )}
          <Text style={{ fontWeight: '800', marginBottom: 8 }}>{live.data.data.route.name}</Text>
          {live.data.data.last_ping && (
            <Text style={{ color: colors.muted, marginBottom: 12 }}>
              Last known position: {live.data.data.last_ping.lat}, {live.data.data.last_ping.lng}
              {'\n'}at {live.data.data.last_ping.recorded_at}
            </Text>
          )}
          <Text style={{ fontWeight: '800', fontSize: 12, textTransform: 'uppercase', color: colors.blue, marginBottom: 6 }}>Stops</Text>
          {live.data.data.stops.map((s: any) => (
            <Text key={s.id} style={{ marginBottom: 4 }}>
              {s.name} <Text style={{ color: colors.muted }}>· ETA {s.eta}</Text>
            </Text>
          ))}
        </Card>
      )}
    </Screen>
  );
}
