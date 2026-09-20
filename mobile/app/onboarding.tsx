import { useRouter } from 'expo-router';
import React, { useRef, useState } from 'react';
import {
  Dimensions, FlatList, Image, NativeScrollEvent, NativeSyntheticEvent, StyleSheet, Text, View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { onboardingStore } from '../src/api/client';
import { Button } from '../src/components/ui';
import { colors, spacing } from '../src/theme/tokens';

const { width } = Dimensions.get('window');

type Slide = { key: string; title: string; body: string; emoji?: string; logo?: boolean };

const SLIDES: Slide[] = [
  {
    key: 'welcome',
    logo: true,
    title: 'Welcome to Mazowe Heights',
    body: 'One app for parents, pupils and teachers — everything about school life in one place, built for patchy connections and busy mornings.',
  },
  {
    key: 'parent',
    emoji: '👨‍👩‍👧',
    title: 'For parents',
    body: 'See your child’s timetable, attendance, grades and term reports, pay school fees, request an exeat, track the school bus, and message the class teacher directly.',
  },
  {
    key: 'student',
    emoji: '🎒',
    title: 'For pupils',
    body: 'Check your timetable and homework, submit assignments, see your grades, browse the library catalogue, and check this week’s dining menu.',
  },
  {
    key: 'teacher',
    emoji: '🍎',
    title: 'For teachers',
    body: 'Take the register, enter assessment marks, set homework, and log merits and demerits — all from your phone, in the classroom.',
  },
  {
    key: 'notices',
    emoji: '🔔',
    title: 'Stay in the loop',
    body: 'Notices, events, and messages come straight to you — no more missed announcements or paper letters that never make it home.',
  },
];

export default function OnboardingScreen() {
  const router = useRouter();
  const listRef = useRef<FlatList<Slide>>(null);
  const [index, setIndex] = useState(0);

  const finish = async () => {
    await onboardingStore.markSeen();
    router.replace('/login');
  };

  const next = () => {
    if (index < SLIDES.length - 1) {
      listRef.current?.scrollToIndex({ index: index + 1 });
    } else {
      finish();
    }
  };

  const onScroll = (e: NativeSyntheticEvent<NativeScrollEvent>) => {
    const i = Math.round(e.nativeEvent.contentOffset.x / width);
    if (i !== index) setIndex(i);
  };

  return (
    <SafeAreaView style={styles.screen}>
      <View style={styles.skipRow}>
        <Button label="Skip" variant="outline" onPress={finish} />
      </View>

      <FlatList
        ref={listRef}
        data={SLIDES}
        keyExtractor={(s) => s.key}
        horizontal
        pagingEnabled
        showsHorizontalScrollIndicator={false}
        onScroll={onScroll}
        scrollEventThrottle={16}
        renderItem={({ item }) => (
          <View style={[styles.slide, { width }]}>
            {item.logo ? (
              <Image source={require('../assets/logo-small.png')} style={styles.logo} resizeMode="contain" />
            ) : (
              <Text style={styles.emoji}>{item.emoji}</Text>
            )}
            <Text style={styles.title}>{item.title}</Text>
            <Text style={styles.body}>{item.body}</Text>
          </View>
        )}
      />

      <View style={styles.dots}>
        {SLIDES.map((s, i) => (
          <View key={s.key} style={[styles.dot, i === index && styles.dotActive]} />
        ))}
      </View>

      <View style={styles.footer}>
        <Button label={index === SLIDES.length - 1 ? 'Get started' : 'Next'} onPress={next} />
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  screen: { flex: 1, backgroundColor: colors.paper },
  skipRow: { alignItems: 'flex-end', paddingHorizontal: spacing.lg, paddingTop: spacing.sm },
  slide: { alignItems: 'center', justifyContent: 'center', padding: spacing.xl },
  emoji: { fontSize: 64, marginBottom: spacing.lg },
  logo: { width: 160, height: 160, marginBottom: spacing.lg },
  title: {
    fontSize: 24, fontWeight: '800', color: colors.ink, textAlign: 'center', marginBottom: spacing.md, letterSpacing: -0.5,
  },
  body: {
    fontSize: 16, lineHeight: 23, color: colors.muted, textAlign: 'center', maxWidth: 340,
  },
  dots: {
    flexDirection: 'row', justifyContent: 'center', gap: 8, marginBottom: spacing.lg,
  },
  dot: {
    width: 8, height: 8, backgroundColor: colors.divider,
  },
  dotActive: { backgroundColor: colors.red, width: 20 },
  footer: { paddingHorizontal: spacing.lg, paddingBottom: spacing.lg },
});
