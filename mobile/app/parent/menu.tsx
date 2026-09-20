import { useQuery } from '@tanstack/react-query';
import React, { useState } from 'react';
import { Pressable, Text, View } from 'react-native';
import { fetchMenuWeek, rateMenuItem } from '../../src/api/portal';
import {
  Card, EmptyState, Header, Loading, Screen,
} from '../../src/components/ui';
import { colors, spacing } from '../../src/theme/tokens';

export default function Menu() {
  const menu = useQuery({ queryKey: ['menu-week'], queryFn: fetchMenuWeek });
  const [rated, setRated] = useState<Record<number, number>>({});

  const rate = async (itemId: number, value: number) => {
    setRated((r) => ({ ...r, [itemId]: value }));
    try {
      await rateMenuItem(itemId, value);
    } catch {
      // best-effort, rating stays optimistic locally
    }
  };

  return (
    <Screen>
      <Header kicker="Boarding" title="This week's menu" />
      {menu.isLoading && <Loading />}
      {menu.data?.map((day) => (
        <Card key={day.id}>
          <Text style={{ fontWeight: '800', marginBottom: 8 }}>{day.day_name}</Text>
          {day.items.map((item) => (
            <View key={item.id} style={{ marginBottom: 10, paddingBottom: 10, borderBottomWidth: 1, borderColor: colors.divider }}>
              <Text style={{ fontSize: 11, fontWeight: '800', color: colors.blue, textTransform: 'uppercase', marginBottom: 2 }}>{item.meal}</Text>
              <Text style={{ color: colors.muted, marginBottom: 6 }}>{item.description}</Text>
              {item.meal === 'breakfast' && (
                <View style={{ flexDirection: 'row', gap: 4 }}>
                  {[1, 2, 3, 4, 5].map((n) => (
                    <Pressable key={n} onPress={() => rate(item.id, n)}>
                      <Text style={{ fontSize: 18, color: (rated[item.id] ?? 0) >= n ? colors.red : colors.divider }}>★</Text>
                    </Pressable>
                  ))}
                </View>
              )}
            </View>
          ))}
        </Card>
      ))}
      {menu.data?.length === 0 && <EmptyState text="No menu published yet." />}
    </Screen>
  );
}
