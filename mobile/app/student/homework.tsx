import { useQuery, useQueryClient } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Text } from 'react-native';
import { useAuth } from '../../src/api/auth';
import { fetchHomework, submitHomework } from '../../src/api/portal';
import {
  Button, Card, EmptyState, Field, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function HomeworkScreen() {
  const { user } = useAuth();
  const studentId = user?.student_id ?? 0;
  const qc = useQueryClient();
  const homework = useQuery({ queryKey: ['homework', studentId], queryFn: () => fetchHomework(studentId), enabled: !!studentId });
  const [notes, setNotes] = useState<Record<number, string>>({});
  const [submittingId, setSubmittingId] = useState<number | null>(null);

  const submit = async (id: number) => {
    setSubmittingId(id);
    try {
      await submitHomework(id, notes[id] ?? '');
      qc.invalidateQueries({ queryKey: ['homework', studentId] });
    } finally {
      setSubmittingId(null);
    }
  };

  return (
    <Screen>
      <Header kicker="Academics" title="Homework" />
      {homework.isLoading && <Loading />}
      {homework.data?.map((h) => (
        <Card key={h.id}>
          <Text style={{ fontWeight: '800' }}>{h.subject}: {h.title}</Text>
          <Text style={{ color: colors.muted, marginBottom: 6 }}>{h.description}</Text>
          <Text style={{ fontSize: 12, fontWeight: '700', color: colors.blue, marginBottom: 10 }}>Due {h.due_date}</Text>
          {h.status === 'submitted' ? (
            <Text style={{ color: colors.blue, fontWeight: '800' }}>✓ Submitted {h.submitted_at}</Text>
          ) : (
            <>
              <Field label="Notes (optional)" value={notes[h.id] ?? ''} onChangeText={(v) => setNotes((n) => ({ ...n, [h.id]: v }))} placeholder="Anything the teacher should know" />
              <Button label={submittingId === h.id ? 'Submitting…' : 'Mark as submitted'} onPress={() => submit(h.id)} disabled={submittingId === h.id} />
            </>
          )}
        </Card>
      ))}
      {homework.data?.length === 0 && <EmptyState text="No homework set yet." />}
    </Screen>
  );
}
