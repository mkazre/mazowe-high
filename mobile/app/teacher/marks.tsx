import { useQuery, useQueryClient } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Alert, Text, View } from 'react-native';
import {
  createAssessment, fetchClassAssessments, fetchClassStudents, fetchSubjects, fetchTeacherClasses, submitAssessmentScores,
} from '../../src/api/portal';
import {
  Button, Card, EmptyState, Field, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function MarksScreen() {
  const classes = useQuery({ queryKey: ['teacher-classes'], queryFn: fetchTeacherClasses });
  const subjects = useQuery({ queryKey: ['subjects'], queryFn: fetchSubjects });
  const [classId, setClassId] = useState<number | null>(null);
  const assessments = useQuery({
    queryKey: ['class-assessments', classId],
    queryFn: () => fetchClassAssessments(classId as number),
    enabled: classId !== null,
  });
  const students = useQuery({
    queryKey: ['class-students', classId],
    queryFn: () => fetchClassStudents(classId as number),
    enabled: classId !== null,
  });
  const qc = useQueryClient();
  const [assessmentId, setAssessmentId] = useState<number | null>(null);
  const [newName, setNewName] = useState('');
  const [scores, setScores] = useState<Record<number, string>>({});
  const [saving, setSaving] = useState(false);

  const addAssessment = async () => {
    if (!classId || !newName || !subjects.data?.length) return;
    const res = await createAssessment({ class_id: classId, subject_id: subjects.data[0].id, name: newName, max_score: 100 });
    setNewName('');
    qc.invalidateQueries({ queryKey: ['class-assessments', classId] });
    setAssessmentId(res.id);
  };

  const save = async () => {
    if (!assessmentId || !students.data) return;
    setSaving(true);
    try {
      const payload = students.data.filter((s) => scores[s.id]).map((s) => ({ student_id: s.id, score: Number(scores[s.id]) }));
      await submitAssessmentScores(assessmentId, payload);
      Alert.alert('Saved', 'Marks have been recorded.');
    } finally {
      setSaving(false);
    }
  };

  return (
    <Screen>
      <Header kicker="Assessment" title="Enter marks" />
      {classes.isLoading && <Loading />}
      <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.lg }}>
        {classes.data?.map((c) => (
          <Button key={c.id} label={c.name} variant={classId === c.id ? 'primary' : 'secondary'} onPress={() => { setClassId(c.id); setAssessmentId(null); }} />
        ))}
      </View>

      {classId !== null && (
        <>
          <Card>
            <Text style={{ fontWeight: '800', marginBottom: 8 }}>Choose or create an assessment</Text>
            <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.sm, marginBottom: spacing.sm }}>
              {assessments.data?.map((a) => (
                <Button key={a.id} label={`${a.subject} — ${a.name}`} variant={assessmentId === a.id ? 'primary' : 'outline'} onPress={() => setAssessmentId(a.id)} />
              ))}
            </View>
            <Field label="New assessment name" value={newName} onChangeText={setNewName} placeholder="Term 1 Test" />
            <Button label="Create assessment" variant="secondary" onPress={addAssessment} disabled={!newName} />
          </Card>

          {assessmentId !== null && (
            <>
              {students.isLoading && <Loading />}
              {students.data?.map((s) => (
                <Card key={s.id}>
                  <Text style={{ fontWeight: '800', marginBottom: 8 }}>{s.first_name} {s.last_name}</Text>
                  <Field label="Score" keyboardType="numeric" value={scores[s.id] ?? ''} onChangeText={(v) => setScores((sc) => ({ ...sc, [s.id]: v }))} placeholder="0–100" />
                </Card>
              ))}
              {students.data?.length === 0 && <EmptyState text="No pupils in this class." />}
              {!!students.data?.length && <Button label={saving ? 'Saving…' : 'Save marks'} onPress={save} disabled={saving} />}
            </>
          )}
        </>
      )}
    </Screen>
  );
}
