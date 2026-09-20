import React from 'react';
import {
  ActivityIndicator, Pressable, ScrollView, StyleSheet, Text, TextInput, TextInputProps, View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { colors, radius, spacing } from '../theme/tokens';

export function Screen({ children, scroll = true }: { children: React.ReactNode; scroll?: boolean }) {
  const Container = scroll ? ScrollView : View;

  return (
    <SafeAreaView style={styles.screen} edges={['top', 'left', 'right']}>
      <Container contentContainerStyle={scroll ? styles.scrollContent : undefined} style={scroll ? undefined : styles.flex}>
        {children}
      </Container>
    </SafeAreaView>
  );
}

export function Header({ kicker, title }: { kicker?: string; title: string }) {
  return (
    <View style={styles.header}>
      {kicker ? <Text style={styles.kicker}>{kicker}</Text> : null}
      <Text style={styles.title}>{title}</Text>
    </View>
  );
}

export function Card({ children, style }: { children: React.ReactNode; style?: object }) {
  return <View style={[styles.card, style]}>{children}</View>;
}

export function Button({
  label, onPress, variant = 'primary', disabled,
}: { label: string; onPress: () => void; variant?: 'primary' | 'secondary' | 'outline'; disabled?: boolean }) {
  return (
    <Pressable
      onPress={onPress}
      disabled={disabled}
      style={({ pressed }) => [
        styles.btn,
        variant === 'primary' && styles.btnPrimary,
        variant === 'secondary' && styles.btnSecondary,
        variant === 'outline' && styles.btnOutline,
        pressed && { opacity: 0.85 },
        disabled && { opacity: 0.5 },
      ]}
    >
      <Text style={[styles.btnLabel, variant === 'outline' && { color: colors.ink }]}>{label}</Text>
    </Pressable>
  );
}

export function Field({ label, ...props }: { label: string } & TextInputProps) {
  return (
    <View style={styles.field}>
      <Text style={styles.fieldLabel}>{label}</Text>
      <TextInput style={styles.input} placeholderTextColor="#9a9895" {...props} />
    </View>
  );
}

export function Loading() {
  return (
    <View style={styles.loading}>
      <ActivityIndicator color={colors.red} size="large" />
    </View>
  );
}

export function EmptyState({ text }: { text: string }) {
  return (
    <View style={styles.empty}>
      <Text style={styles.emptyText}>{text}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  flex: { flex: 1 },
  screen: { flex: 1, backgroundColor: colors.paper },
  scrollContent: { padding: spacing.lg, paddingBottom: spacing.xl * 2 },
  header: { marginBottom: spacing.lg },
  kicker: {
    fontSize: 12, fontWeight: '800', letterSpacing: 1.5, textTransform: 'uppercase',
    color: colors.blue, marginBottom: 6,
  },
  title: { fontSize: 26, fontWeight: '800', color: colors.ink, letterSpacing: -0.5 },
  card: {
    borderWidth: 2, borderColor: colors.ink, borderRadius: radius, backgroundColor: colors.paper,
    padding: spacing.md, marginBottom: spacing.md,
  },
  btn: {
    borderWidth: 2, borderColor: colors.ink, borderRadius: radius, paddingVertical: 14,
    paddingHorizontal: 20, alignItems: 'center', minHeight: 48, justifyContent: 'center',
  },
  btnPrimary: { backgroundColor: colors.red, borderColor: colors.red },
  btnSecondary: { backgroundColor: colors.blue, borderColor: colors.blue },
  btnOutline: { backgroundColor: colors.paper },
  btnLabel: { color: colors.paper, fontWeight: '800', letterSpacing: 0.5, textTransform: 'uppercase', fontSize: 13 },
  field: { marginBottom: spacing.md },
  fieldLabel: { fontSize: 13, fontWeight: '600', marginBottom: 6, color: colors.ink },
  input: {
    borderWidth: 1, borderColor: colors.divider, borderRadius: radius, padding: 12,
    fontSize: 16, backgroundColor: colors.paper, color: colors.ink,
  },
  loading: { flex: 1, alignItems: 'center', justifyContent: 'center', backgroundColor: colors.paper },
  empty: { padding: spacing.lg, alignItems: 'center' },
  emptyText: { color: colors.muted, fontSize: 14 },
});
