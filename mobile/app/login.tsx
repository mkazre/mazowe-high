import React, { useState } from 'react';
import { Image, Text, View } from 'react-native';
import { useAuth } from '../src/api/auth';
import { Button, Field, Screen } from '../src/components/ui';
import { colors, spacing } from '../src/theme/tokens';

export default function LoginScreen() {
  const { login } = useAuth();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [submitting, setSubmitting] = useState(false);

  const onSubmit = async () => {
    setError(null);
    setSubmitting(true);
    try {
      await login(email.trim(), password);
    } catch {
      setError('Incorrect email or password.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <Screen scroll={false}>
      <View style={{ flex: 1, justifyContent: 'center', padding: spacing.lg }}>
        <View style={{ alignItems: 'center', marginBottom: spacing.xl }}>
          <View style={{
            width: 56, height: 64, backgroundColor: colors.blue, alignItems: 'center', justifyContent: 'center', marginBottom: spacing.md,
          }}
          >
            <Text style={{ color: '#fff', fontWeight: '800', fontSize: 20 }}>MH</Text>
          </View>
          <Text style={{ fontSize: 22, fontWeight: '800', color: colors.ink }}>Mazowe Heights</Text>
          <Text style={{ fontSize: 12, letterSpacing: 2, textTransform: 'uppercase', color: colors.blue, marginTop: 4 }}>
            Parent · Student · Teacher
          </Text>
        </View>

        <Field label="Email" autoCapitalize="none" keyboardType="email-address" value={email} onChangeText={setEmail} placeholder="you@example.co.zw" />
        <Field label="Password" secureTextEntry value={password} onChangeText={setPassword} placeholder="••••••••" />

        {error ? <Text style={{ color: colors.red, marginBottom: spacing.md }}>{error}</Text> : null}

        <Button label={submitting ? 'Signing in…' : 'Sign in'} onPress={onSubmit} disabled={submitting || !email || !password} />

        <Text style={{ color: colors.muted, fontSize: 12, marginTop: spacing.lg, textAlign: 'center' }}>
          Portal access opens with the founding intake in January 2027. Staff can sign in with their school account now.
        </Text>
      </View>
    </Screen>
  );
}
