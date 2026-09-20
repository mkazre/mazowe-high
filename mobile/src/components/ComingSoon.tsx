import React from 'react';
import { Text } from 'react-native';
import { Card, Header, Screen } from './ui';
import { colors } from '../theme/tokens';

export default function ComingSoon({ kicker, title, note }: { kicker: string; title: string; note?: string }) {
  return (
    <Screen>
      <Header kicker={kicker} title={title} />
      <Card>
        <Text style={{ color: colors.muted, lineHeight: 20 }}>
          {note ?? 'This screen activates once the founding intake is enrolled and this module is switched on in the School Manager backend. The layout is in place — it just needs real school data behind it.'}
        </Text>
      </Card>
    </Screen>
  );
}
