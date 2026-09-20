import { useQuery } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Text } from 'react-native';
import { fetchLoans, searchLibrary } from '../../src/api/portal';
import {
  Card, EmptyState, Field, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function LibraryScreen() {
  const [q, setQ] = useState('');
  const search = useQuery({ queryKey: ['library-search', q], queryFn: () => searchLibrary(q) });
  const loans = useQuery({ queryKey: ['loans'], queryFn: fetchLoans });

  return (
    <Screen>
      <Header kicker="School Life" title="Library" />

      <Field label="Search the catalogue" value={q} onChangeText={setQ} placeholder="Title or author" />
      {search.isLoading && <Loading />}
      {search.data?.map((b) => (
        <Card key={b.id}>
          <Text style={{ fontWeight: '800' }}>{b.title}</Text>
          <Text style={{ color: colors.muted }}>{b.author} · {b.copies_total} copies</Text>
        </Card>
      ))}
      {search.data?.length === 0 && <EmptyState text="No matching titles." />}

      <Text style={{ fontWeight: '800', fontSize: 13, textTransform: 'uppercase', color: colors.blue, marginTop: spacing.lg, marginBottom: spacing.sm }}>My loans</Text>
      {loans.data?.map((l) => (
        <Card key={l.id}>
          <Text style={{ fontWeight: '800' }}>{l.title}</Text>
          <Text style={{ color: colors.muted }}>Due {l.due_at}{l.returned_at ? ' · Returned' : ''}</Text>
        </Card>
      ))}
      {loans.data?.length === 0 && <EmptyState text="No active loans." />}
    </Screen>
  );
}
