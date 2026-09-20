import { useQuery } from '@tanstack/react-query';
import React from 'react';
import { Text } from 'react-native';
import { useAuth } from '../../src/api/auth';
import { fetchTimetable } from '../../src/api/portal';
import {
  Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors } from '../../src/theme/tokens';

const DAY_NAMES: Record<number, string> = { 1: 'Monday', 2: 'Tuesday', 3: 'Wednesday', 4: 'Thursday', 5: 'Friday' };

export default function TimetableScreen() {
  const { user } = useAuth();
  const studentId = user?.student_id ?? 0;
  const timetable = useQuery({ queryKey: ['timetable', studentId], queryFn: () => fetchTimetable(studentId), enabled: !!studentId });

  if (!studentId) {
    return (
      <Screen>
        <Header kicker="Academics" title="Timetable" />
        <EmptyState text="No student record is linked to this account yet." />
      </Screen>
    );
  }

  return (
    <Screen>
      <Header kicker="Academics" title="Timetable" />
      {timetable.isLoading && <Loading />}
      {timetable.data && Object.entries(timetable.data).map(([day, entries]) => (
        <Card key={day}>
          <Text style={{ fontWeight: '800', marginBottom: 8 }}>{DAY_NAMES[Number(day)]}</Text>
          {entries.length === 0 && <Text style={{ color: colors.muted }}>No lessons.</Text>}
          {entries.map((e, i) => (
            <Text key={i} style={{ marginBottom: 4 }}>
              {e.start_time}–{e.end_time} · <Text style={{ fontWeight: '700' }}>{e.subject}</Text> · {e.room}
            </Text>
          ))}
        </Card>
      ))}
    </Screen>
  );
}
