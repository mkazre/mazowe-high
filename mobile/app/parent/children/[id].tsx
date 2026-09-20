import { useQuery } from '@tanstack/react-query';
import { useLocalSearchParams } from 'expo-router';
import React, { useState } from 'react';
import { Pressable, Text, View } from 'react-native';
import {
  fetchAttendance, fetchConduct, fetchGrades, fetchHomework, fetchReports, fetchTimetable,
} from '../../../src/api/portal';
import {
  Card, EmptyState, Header, Loading, Screen,
} from '../../../src/components/ui';
import { colors, spacing } from '../../../src/theme/tokens';

const DAY_NAMES: Record<number, string> = { 1: 'Monday', 2: 'Tuesday', 3: 'Wednesday', 4: 'Thursday', 5: 'Friday' };
const TABS = ['Timetable', 'Attendance', 'Grades', 'Reports', 'Conduct', 'Homework'] as const;

export default function ChildProfile() {
  const { id } = useLocalSearchParams<{ id: string }>();
  const studentId = Number(id);
  const [tab, setTab] = useState<typeof TABS[number]>('Timetable');

  const timetable = useQuery({ queryKey: ['timetable', studentId], queryFn: () => fetchTimetable(studentId), enabled: tab === 'Timetable' });
  const attendance = useQuery({ queryKey: ['attendance', studentId], queryFn: () => fetchAttendance(studentId), enabled: tab === 'Attendance' });
  const grades = useQuery({ queryKey: ['grades', studentId], queryFn: () => fetchGrades(studentId), enabled: tab === 'Grades' });
  const reports = useQuery({ queryKey: ['reports', studentId], queryFn: () => fetchReports(studentId), enabled: tab === 'Reports' });
  const conduct = useQuery({ queryKey: ['conduct', studentId], queryFn: () => fetchConduct(studentId), enabled: tab === 'Conduct' });
  const homework = useQuery({ queryKey: ['homework', studentId], queryFn: () => fetchHomework(studentId), enabled: tab === 'Homework' });

  return (
    <Screen>
      <Header kicker="My children" title={`Pupil #${studentId}`} />

      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: 8, marginBottom: spacing.lg }}>
        {TABS.map((t) => (
          <Pressable key={t} onPress={() => setTab(t)} style={{
            borderWidth: 2, borderColor: colors.ink, paddingVertical: 8, paddingHorizontal: 12,
            backgroundColor: tab === t ? colors.blue : colors.paper,
          }}
          >
            <Text style={{ color: tab === t ? '#fff' : colors.ink, fontWeight: '700', fontSize: 12 }}>{t}</Text>
          </Pressable>
        ))}
      </View>

      {tab === 'Timetable' && (
        <>
          {timetable.isLoading && <Loading />}
          {timetable.data && Object.entries(timetable.data).map(([day, entries]) => (
            <Card key={day}>
              <Text style={{ fontWeight: '800', marginBottom: 8 }}>{DAY_NAMES[Number(day)]}</Text>
              {entries.length === 0 && <Text style={{ color: colors.muted }}>No lessons.</Text>}
              {entries.map((e, i) => (
                <Text key={i} style={{ marginBottom: 4 }}>
                  {e.start_time}–{e.end_time} · <Text style={{ fontWeight: '700' }}>{e.subject}</Text> · {e.room} · {e.teacher}
                </Text>
              ))}
            </Card>
          ))}
        </>
      )}

      {tab === 'Attendance' && (
        <>
          {attendance.isLoading && <Loading />}
          {attendance.data && (
            <Card>
              <Text style={{ fontWeight: '800', fontSize: 20, color: colors.blue }}>{attendance.data.summary.rate}%</Text>
              <Text style={{ color: colors.muted }}>{attendance.data.summary.present} of {attendance.data.summary.total} days present</Text>
            </Card>
          )}
          {attendance.data?.data.map((a) => (
            <Card key={a.id}>
              <Text>{a.mark_date} — <Text style={{ fontWeight: '700', textTransform: 'capitalize' }}>{a.status}</Text></Text>
            </Card>
          ))}
        </>
      )}

      {tab === 'Grades' && (
        <>
          {grades.isLoading && <Loading />}
          {grades.data?.map((g, i) => (
            <Card key={i}>
              <Text style={{ fontWeight: '800' }}>{g.subject} — {g.assessment}</Text>
              <Text style={{ color: colors.blue, fontWeight: '800', fontSize: 18 }}>{g.score}/{g.max_score}</Text>
              {g.comment ? <Text style={{ color: colors.muted }}>{g.comment}</Text> : null}
            </Card>
          ))}
          {grades.data?.length === 0 && <EmptyState text="No grades recorded yet." />}
        </>
      )}

      {tab === 'Reports' && (
        <>
          {reports.isLoading && <Loading />}
          {reports.data?.map((r) => (
            <Card key={r.id}>
              <Text style={{ color: colors.muted, marginBottom: 8 }}>{r.overall_comment}</Text>
              {r.comments.map((c, i) => (
                <Text key={i} style={{ marginBottom: 4 }}>
                  {c.subject}: <Text style={{ fontWeight: '800' }}>{c.grade}</Text> (effort {c.effort}) — {c.comment}
                </Text>
              ))}
            </Card>
          ))}
          {reports.data?.length === 0 && <EmptyState text="No published reports yet." />}
        </>
      )}

      {tab === 'Conduct' && (
        <>
          {conduct.isLoading && <Loading />}
          {conduct.data && (
            <Card>
              <Text>Merits: <Text style={{ fontWeight: '800', color: colors.blue }}>{conduct.data.summary.merits}</Text> · Demerits: <Text style={{ fontWeight: '800', color: colors.red }}>{conduct.data.summary.demerits}</Text></Text>
            </Card>
          )}
          {conduct.data?.data.map((c) => (
            <Card key={c.id}>
              <Text style={{ fontWeight: '700', color: c.type === 'merit' ? colors.blue : colors.red, textTransform: 'uppercase', fontSize: 12 }}>{c.type} · {c.points}pt</Text>
              <Text>{c.reason}</Text>
            </Card>
          ))}
        </>
      )}

      {tab === 'Homework' && (
        <>
          {homework.isLoading && <Loading />}
          {homework.data?.map((h) => (
            <Card key={h.id}>
              <Text style={{ fontWeight: '800' }}>{h.subject}: {h.title}</Text>
              <Text style={{ color: colors.muted, marginBottom: 4 }}>{h.description}</Text>
              <Text style={{ fontSize: 12, textTransform: 'uppercase', fontWeight: '700', color: h.status === 'submitted' ? colors.blue : colors.red }}>
                Due {h.due_date} · {h.status ?? 'pending'}
              </Text>
            </Card>
          ))}
          {homework.data?.length === 0 && <EmptyState text="No homework set yet." />}
        </>
      )}
    </Screen>
  );
}
