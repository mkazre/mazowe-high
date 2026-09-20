import { useQuery } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Text } from 'react-native';
import { fetchClassStudents, fetchTeacherClasses } from '../../src/api/portal';
import {
  Button, Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors } from '../../src/theme/tokens';

export default function ClassesScreen() {
  const classes = useQuery({ queryKey: ['teacher-classes'], queryFn: fetchTeacherClasses });
  const [openId, setOpenId] = useState<number | null>(null);
  const students = useQuery({
    queryKey: ['class-students', openId],
    queryFn: () => fetchClassStudents(openId as number),
    enabled: openId !== null,
  });

  return (
    <Screen>
      <Header kicker="Academics" title="My classes" />
      {classes.isLoading && <Loading />}
      {classes.data?.map((c) => (
        <Card key={c.id}>
          <Text style={{ fontWeight: '800', marginBottom: 4 }}>{c.name}</Text>
          <Text style={{ color: colors.muted, marginBottom: 10 }}>{c.student_count} pupils</Text>
          <Button label={openId === c.id ? 'Hide roster' : 'View roster'} variant="outline" onPress={() => setOpenId(openId === c.id ? null : c.id)} />
          {openId === c.id && students.data?.map((s) => (
            <Text key={s.id} style={{ marginTop: 8 }}>{s.first_name} {s.last_name} <Text style={{ color: colors.muted }}>· {s.admission_number}</Text></Text>
          ))}
        </Card>
      ))}
      {classes.data?.length === 0 && <EmptyState text="No classes assigned yet." />}
    </Screen>
  );
}
