import { useQuery } from '@tanstack/react-query';
import React from 'react';
import { Text } from 'react-native';
import { fetchEvents } from '../../src/api/content';
import { Card, EmptyState, Header, Loading, Screen } from '../../src/components/ui';
import { colors } from '../../src/theme/tokens';

export default function Events() {
  const events = useQuery({ queryKey: ['events'], queryFn: fetchEvents });

  return (
    <Screen>
      <Header kicker="School Life" title="Events calendar" />
      {events.isLoading && <Loading />}
      {events.data?.map((e) => (
        <Card key={e.id}>
          <Text style={{ fontWeight: '800' }}>{e.title}</Text>
          <Text style={{ color: colors.muted }}>{e.location}</Text>
          <Text style={{ color: colors.blue, fontWeight: '700', marginTop: 4 }}>{e.tag}</Text>
        </Card>
      ))}
      {events.data?.length === 0 && <EmptyState text="No events scheduled." />}
    </Screen>
  );
}
