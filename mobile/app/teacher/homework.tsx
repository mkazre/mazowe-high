import { useQuery } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Alert, Text, View } from 'react-native';
import { createHomework, fetchSubjects, fetchTeacherClasses } from '../../src/api/portal';
import {
  Button, Field, Header, Screen,
} from '../../src/components/ui';
import { spacing } from '../../src/theme/tokens';

export default function HomeworkScreen() {
  const classes = useQuery({ queryKey: ['teacher-classes'], queryFn: fetchTeacherClasses });
  const subjects = useQuery({ queryKey: ['subjects'], queryFn: fetchSubjects });
  const [classId, setClassId] = useState<number | null>(null);
  const [subjectId, setSubjectId] = useState<number | null>(null);
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [dueDate, setDueDate] = useState('');
  const [saving, setSaving] = useState(false);

  const save = async () => {
    if (!classId || !subjectId || !title) return;
    setSaving(true);
    try {
      await createHomework({ class_id: classId, subject_id: subjectId, title, description, due_date: dueDate || new Date().toISOString().slice(0, 10) });
      setTitle(''); setDescription(''); setDueDate('');
      Alert.alert('Set', 'Homework has been set for the class.');
    } finally {
      setSaving(false);
    }
  };

  return (
    <Screen>
      <Header kicker="Academics" title="Set homework" />

      <Text style={{ fontWeight: '700', marginBottom: 8 }}>Class</Text>
      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.md }}>
        {classes.data?.map((c) => (
          <Button key={c.id} label={c.name} variant={classId === c.id ? 'primary' : 'secondary'} onPress={() => setClassId(c.id)} />
        ))}
      </View>

      <Text style={{ fontWeight: '700', marginBottom: 8 }}>Subject</Text>
      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.md }}>
        {subjects.data?.map((s) => (
          <Button key={s.id} label={s.name} variant={subjectId === s.id ? 'primary' : 'secondary'} onPress={() => setSubjectId(s.id)} />
        ))}
      </View>

      <Field label="Title" value={title} onChangeText={setTitle} placeholder="Algebra worksheet — Chapter 4" />
      <Field label="Description" value={description} onChangeText={setDescription} placeholder="Instructions for pupils" />
      <Field label="Due date (YYYY-MM-DD)" value={dueDate} onChangeText={setDueDate} placeholder="2027-02-10" />

      <Button label={saving ? 'Setting…' : 'Set homework'} onPress={save} disabled={saving || !classId || !subjectId || !title} />
    </Screen>
  );
}
