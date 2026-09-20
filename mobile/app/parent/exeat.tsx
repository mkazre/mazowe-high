import { useQuery, useQueryClient } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Alert, Text } from 'react-native';
import { createExeat, fetchExeats } from '../../src/api/portal';
import {
  Button, Card, EmptyState, Field, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors } from '../../src/theme/tokens';

export default function Exeat() {
  const qc = useQueryClient();
  const exeats = useQuery({ queryKey: ['exeats'], queryFn: fetchExeats });
  const [reason, setReason] = useState('');
  const [depart, setDepart] = useState('');
  const [ret, setRet] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const submit = async () => {
    if (!reason || !depart || !ret) return;
    setSubmitting(true);
    try {
      await createExeat({ reason, depart_at: depart, return_at: ret });
      setReason(''); setDepart(''); setRet('');
      qc.invalidateQueries({ queryKey: ['exeats'] });
      Alert.alert('Request sent', 'Your exeat request has been sent for approval.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <Screen>
      <Header kicker="Boarding" title="Exeat requests" />

      <Card>
        <Text style={{ fontWeight: '800', marginBottom: 10 }}>New request</Text>
        <Field label="Reason" value={reason} onChangeText={setReason} placeholder="Family function" />
        <Field label="Depart (YYYY-MM-DD HH:MM:SS)" value={depart} onChangeText={setDepart} placeholder="2027-02-14 15:00:00" />
        <Field label="Return (YYYY-MM-DD HH:MM:SS)" value={ret} onChangeText={setRet} placeholder="2027-02-16 18:00:00" />
        <Button label={submitting ? 'Sending…' : 'Send request'} onPress={submit} disabled={submitting} />
      </Card>

      {exeats.isLoading && <Loading />}
      {exeats.data?.map((e) => (
        <Card key={e.id}>
          <Text style={{ fontWeight: '800' }}>{e.reason}</Text>
          <Text style={{ color: colors.muted }}>{e.depart_at} → {e.return_at}</Text>
          <Text style={{ color: colors.blue, fontWeight: '700', marginTop: 4, textTransform: 'uppercase', fontSize: 12 }}>{e.status}</Text>
        </Card>
      ))}
      {exeats.data?.length === 0 && <EmptyState text="No exeat requests yet." />}
    </Screen>
  );
}
