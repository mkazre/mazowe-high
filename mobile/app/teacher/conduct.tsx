import { useQuery } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Alert, Text, View } from 'react-native';
import { createConduct, fetchClassStudents, fetchTeacherClasses } from '../../src/api/portal';
import {
  Button, Field, Header, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function ConductScreen() {
  const classes = useQuery({ queryKey: ['teacher-classes'], queryFn: fetchTeacherClasses });
  const [classId, setClassId] = useState<number | null>(null);
  const students = useQuery({
    queryKey: ['class-students', classId],
    queryFn: () => fetchClassStudents(classId as number),
    enabled: classId !== null,
  });
  const [studentId, setStudentId] = useState<number | null>(null);
  const [type, setType] = useState<'merit' | 'demerit'>('merit');
  const [reason, setReason] = useState('');
  const [saving, setSaving] = useState(false);

  const save = async () => {
    if (!studentId || !reason) return;
    setSaving(true);
    try {
      await createConduct({ student_id: studentId, type, points: 1, reason });
      setReason('');
      Alert.alert('Recorded', `A ${type} has been logged.`);
    } finally {
      setSaving(false);
    }
  };

  return (
    <Screen>
      <Header kicker="Pastoral" title="Conduct — merits & demerits" />

      <Text style={{ fontWeight: '700', marginBottom: 8 }}>Class</Text>
      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.md }}>
        {classes.data?.map((c) => (
          <Button key={c.id} label={c.name} variant={classId === c.id ? 'primary' : 'secondary'} onPress={() => { setClassId(c.id); setStudentId(null); }} />
        ))}
      </View>

      {classId !== null && (
        <>
          <Text style={{ fontWeight: '700', marginBottom: 8 }}>Pupil</Text>
          <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.md }}>
            {students.data?.map((s) => (
              <Button key={s.id} label={`${s.first_name} ${s.last_name}`} variant={studentId === s.id ? 'primary' : 'secondary'} onPress={() => setStudentId(s.id)} />
            ))}
          </View>
        </>
      )}

      <Text style={{ fontWeight: '700', marginBottom: 8 }}>Type</Text>
      <View style={{ flexDirection: 'row', gap: spacing.sm, marginBottom: spacing.md }}>
        <Button label="Merit" variant={type === 'merit' ? 'primary' : 'secondary'} onPress={() => setType('merit')} />
        <Button label="Demerit" variant={type === 'demerit' ? 'primary' : 'secondary'} onPress={() => setType('demerit')} />
      </View>

      <Field label="Reason" value={reason} onChangeText={setReason} placeholder="Excellent contribution in class" />
      <Button label={saving ? 'Saving…' : 'Save'} onPress={save} disabled={saving || !studentId || !reason} />
    </Screen>
  );
}
