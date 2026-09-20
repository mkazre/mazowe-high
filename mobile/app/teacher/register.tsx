import { useQuery } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Alert, Pressable, Text, View } from 'react-native';
import { fetchClassStudents, fetchTeacherClasses, submitAttendanceBulk } from '../../src/api/portal';
import {
  Button, Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

const STATUSES = ['present', 'late', 'absent'] as const;

export default function RegisterScreen() {
  const classes = useQuery({ queryKey: ['teacher-classes'], queryFn: fetchTeacherClasses });
  const [classId, setClassId] = useState<number | null>(null);
  const students = useQuery({
    queryKey: ['class-students', classId],
    queryFn: () => fetchClassStudents(classId as number),
    enabled: classId !== null,
  });
  const [marks, setMarks] = useState<Record<number, string>>({});
  const [saving, setSaving] = useState(false);

  const setStatus = (studentId: number, status: string) => setMarks((m) => ({ ...m, [studentId]: status }));

  const save = async () => {
    if (classId === null || !students.data) return;
    setSaving(true);
    try {
      const payload = students.data.map((s) => ({ student_id: s.id, status: marks[s.id] ?? 'present' }));
      await submitAttendanceBulk(classId, new Date().toISOString().slice(0, 10), payload);
      Alert.alert('Register saved', 'Attendance has been recorded for today.');
    } finally {
      setSaving(false);
    }
  };

  return (
    <Screen>
      <Header kicker="Daily operations" title="Take register" />
      {classes.isLoading && <Loading />}
      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.lg }}>
        {classes.data?.map((c) => (
          <Button key={c.id} label={c.name} variant={classId === c.id ? 'primary' : 'secondary'} onPress={() => setClassId(c.id)} />
        ))}
      </View>

      {classId !== null && (
        <>
          {students.isLoading && <Loading />}
          {students.data?.map((s) => (
            <Card key={s.id}>
              <Text style={{ fontWeight: '800', marginBottom: 8 }}>{s.first_name} {s.last_name}</Text>
              <View style={{ flexDirection: 'row', gap: 8 }}>
                {STATUSES.map((st) => (
                  <Pressable
                    key={st}
                    onPress={() => setStatus(s.id, st)}
                    style={{
                      borderWidth: 2, borderColor: colors.ink, paddingVertical: 6, paddingHorizontal: 10,
                      backgroundColor: (marks[s.id] ?? 'present') === st ? colors.blue : colors.paper,
                    }}
                  >
                    <Text style={{ color: (marks[s.id] ?? 'present') === st ? '#fff' : colors.ink, fontSize: 12, textTransform: 'capitalize' }}>{st}</Text>
                  </Pressable>
                ))}
              </View>
            </Card>
          ))}
          {students.data?.length === 0 && <EmptyState text="No pupils in this class." />}
          {!!students.data?.length && <Button label={saving ? 'Saving…' : 'Save register'} onPress={save} disabled={saving} />}
        </>
      )}
    </Screen>
  );
}
