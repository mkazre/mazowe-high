import { useLocalSearchParams } from 'expo-router';
import React from 'react';
import ComingSoon from '../../../src/components/ComingSoon';

export default function ChildProfile() {
  const { id } = useLocalSearchParams<{ id: string }>();

  return <ComingSoon kicker="My children" title={`Pupil profile #${id}`} note="Timetable, attendance, grades and conduct for this pupil will appear here once enrolled." />;
}
