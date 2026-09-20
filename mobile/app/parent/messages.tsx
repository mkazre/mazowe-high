import { useQuery, useQueryClient } from '@tanstack/react-query';
import React, { useState } from 'react';
import { FlatList, Text, View } from 'react-native';
import { fetchThreadMessages, fetchThreads, sendThreadMessage } from '../../src/api/portal';
import {
  Button, Card, EmptyState, Field, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function Messages() {
  const qc = useQueryClient();
  const threads = useQuery({ queryKey: ['threads'], queryFn: fetchThreads });
  const [openId, setOpenId] = useState<number | null>(null);
  const [draft, setDraft] = useState('');

  const thread = useQuery({
    queryKey: ['thread', openId],
    queryFn: () => fetchThreadMessages(openId as number),
    enabled: openId !== null,
  });

  const send = async () => {
    if (!draft.trim() || openId === null) return;
    await sendThreadMessage(openId, draft.trim());
    setDraft('');
    qc.invalidateQueries({ queryKey: ['thread', openId] });
  };

  if (openId !== null) {
    return (
      <Screen scroll={false}>
        <View style={{ padding: spacing.lg, flex: 1 }}>
          <Button label="← Back to messages" variant="outline" onPress={() => setOpenId(null)} />
          <Header title={threads.data?.find((t) => t.id === openId)?.subject ?? 'Thread'} />
          <FlatList
            data={thread.data ?? []}
            keyExtractor={(m) => String(m.id)}
            style={{ flex: 1 }}
            renderItem={({ item }) => (
              <View style={{ marginBottom: spacing.sm }}>
                <Text style={{ fontWeight: '800', fontSize: 12, color: colors.blue }}>{item.sender_label}</Text>
                <Text>{item.body}</Text>
              </View>
            )}
          />
          <Field label="Reply" value={draft} onChangeText={setDraft} placeholder="Type a message…" />
          <Button label="Send" onPress={send} disabled={!draft.trim()} />
        </View>
      </Screen>
    );
  }

  return (
    <Screen>
      <Header kicker="Comms" title="Messages" />
      {threads.isLoading && <Loading />}
      {threads.data?.map((t) => (
        <Card key={t.id} style={{ padding: 0 }}>
          <Button label={t.subject} variant="outline" onPress={() => setOpenId(t.id)} />
          {t.last_message ? (
            <Text style={{ color: colors.muted, padding: 12, paddingTop: 8 }} numberOfLines={2}>
              {t.last_message.sender_label}: {t.last_message.body}
            </Text>
          ) : null}
        </Card>
      ))}
      {threads.data?.length === 0 && <EmptyState text="No message threads yet." />}
    </Screen>
  );
}
