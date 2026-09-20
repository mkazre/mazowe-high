import { useQuery } from '@tanstack/react-query';
import React from 'react';
import { Text } from 'react-native';
import { fetchPage } from '../../src/api/content';
import {
  Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';

export default function ClubsScreen() {
  const page = useQuery({ queryKey: ['page', 'school-life', 'clubs'], queryFn: () => fetchPage('school-life', 'clubs') });
  const listBlock = page.data?.blocks.find((b) => b.type === 'list');

  return (
    <Screen>
      <Header kicker="School Life" title="Clubs & societies" />
      {page.isLoading && <Loading />}
      {listBlock?.data.items?.map((item: string, i: number) => (
        <Card key={i}>
          <Text style={{ fontWeight: '700' }}>{item}</Text>
        </Card>
      ))}
      {page.data && !listBlock && <EmptyState text="No societies published yet." />}
    </Screen>
  );
}
