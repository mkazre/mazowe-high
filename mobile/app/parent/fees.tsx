import { useQuery, useQueryClient } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Alert, Text, View } from 'react-native';
import { fetchInvoices, payInitiate, payVerify } from '../../src/api/portal';
import {
  Button, Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

const METHODS = ['ecocash', 'zipit', 'card', 'innbucks', 'cash', 'paypal'];

function money(cents: number) {
  return `US$ ${(cents / 100).toFixed(2)}`;
}

export default function Fees() {
  const qc = useQueryClient();
  const invoices = useQuery({ queryKey: ['invoices'], queryFn: fetchInvoices });
  const [payingId, setPayingId] = useState<number | null>(null);

  const pay = async (invoiceId: number, method: string) => {
    setPayingId(invoiceId);
    try {
      const init = await payInitiate(invoiceId, method);
      // Sandbox: no real gateway is connected, so we simulate the confirmation
      // step immediately instead of waiting on a webhook that will never fire.
      await payVerify(init.payment_id);
      Alert.alert('Payment successful (test mode)', `Reference ${init.reference}. No live merchant account is connected yet — this confirms the flow works end to end.`);
      qc.invalidateQueries({ queryKey: ['invoices'] });
    } catch {
      Alert.alert('Payment failed', 'Something went wrong — please try again.');
    } finally {
      setPayingId(null);
    }
  };

  return (
    <Screen>
      <Header kicker="Portal" title="Fees & Invoices" />
      {invoices.isLoading && <Loading />}
      {invoices.data?.map((inv) => (
        <Card key={inv.id}>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginBottom: 8 }}>
            <Text style={{ fontWeight: '800' }}>{inv.invoice_number}</Text>
            <Text style={{ color: inv.status === 'paid' ? colors.blue : colors.red, fontWeight: '800', textTransform: 'uppercase', fontSize: 12 }}>
              {inv.status}
            </Text>
          </View>
          <Text style={{ color: colors.muted, marginBottom: 4 }}>Total: {money(inv.total_cents)}</Text>
          <Text style={{ color: colors.muted, marginBottom: 12 }}>Paid: {money(inv.paid_cents)} · Due {inv.due_date}</Text>
          {inv.status !== 'paid' && (
            <View>
              <Text style={{ fontSize: 12, fontWeight: '700', marginBottom: 6 }}>Pay with (test mode):</Text>
              <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: spacing.xs }}>
                {METHODS.map((m) => (
                  <Button
                    key={m}
                    label={payingId === inv.id ? '…' : m}
                    variant="secondary"
                    disabled={payingId === inv.id}
                    onPress={() => pay(inv.id, m)}
                  />
                ))}
              </View>
            </View>
          )}
        </Card>
      ))}
      {invoices.data?.length === 0 && <EmptyState text="No invoices yet." />}
    </Screen>
  );
}
