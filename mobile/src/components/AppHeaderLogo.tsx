import React from 'react';
import { Image, StyleSheet } from 'react-native';

export default function AppHeaderLogo() {
  return (
    // eslint-disable-next-line global-require
    <Image source={require('../../assets/logo-small.png')} style={styles.logo} resizeMode="contain" />
  );
}

const styles = StyleSheet.create({
  logo: { width: 34, height: 34 },
});
