import { useQuery } from '@tanstack/react-query';
import React from 'react';
import { Text } from 'react-native';
import { useAuth } from '../../src/api/auth';
import { fetchGrades, fetchReports } from '../../src/api/portal';
import {
  Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function GradesScreen() {
  const { user } = useAuth();
  const studentId = user?.student_id ?? 0;
  const grades = useQuery({ queryKey: ['grades', studentId], queryFn: () => fetchGrades(studentId), enabled: !!studentId });
  const reports = useQuery({ queryKey: ['reports', studentId], queryFn: () => fetchReports(studentId), enabled: !!studentId });

  return (
    <Screen>
      <Header kicker="Academics" title="Grades & reports" />

      <Text style={{ fontWeight: '800', fontSize: 13, textTransform: 'uppercase', color: colors.blue, marginBottom: spacing.sm }}>Assessment scores</Text>
      {grades.isLoading && <Loading />}
      {grades.data?.map((g, i) => (
        <Card key={i}>
          <Text style={{ fontWeight: '800' }}>{g.subject} — {g.assessment}</Text>
          <Text style={{ color: colors.blue, fontWeight: '800', fontSize: 18 }}>{g.score}/{g.max_score}</Text>
          {g.comment ? <Text style={{ color: colors.muted }}>{g.comment}</Text> : null}
        </Card>
      ))}
      {grades.data?.length === 0 && <EmptyState text="No grades recorded yet." />}

      <Text style={{ fontWeight: '800', fontSize: 13, textTransform: 'uppercase', color: colors.blue, marginTop: spacing.lg, marginBottom: spacing.sm }}>Term reports</Text>
      {reports.isLoading && <Loading />}
      {reports.data?.map((r) => (
        <Card key={r.id}>
          <Text style={{ color: colors.muted, marginBottom: 8 }}>{r.overall_comment}</Text>
          {r.comments.map((c, i) => (
            <Text key={i} style={{ marginBottom: 4 }}>
              {c.subject}: <Text style={{ fontWeight: '800' }}>{c.grade}</Text> — {c.comment}
            </Text>
          ))}
        </Card>
      ))}
      {reports.data?.length === 0 && <EmptyState text="No published reports yet." />}
    </Screen>
  );
}
