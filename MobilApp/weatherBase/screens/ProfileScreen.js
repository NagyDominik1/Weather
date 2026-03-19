import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Alert,
  SafeAreaView,
  ScrollView,
} from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { logout, isLoggedIn } from '../services/api';

export default function ProfileScreen({ navigation }) {
  const [email, setEmail] = useState('');
  const [loggedIn, setLoggedIn] = useState(false);

  useEffect(() => {
    checkStatus();
  }, []);

  useEffect(() => {
    const unsubscribe = navigation.addListener('focus', () => {
      checkStatus();
    });
    return unsubscribe;
  }, [navigation]);

  const checkStatus = async () => {
    const status = await isLoggedIn();
    setLoggedIn(status);

    if (status) {
      const userEmail = await AsyncStorage.getItem('user_email');
      setEmail(userEmail || 'Ismeretlen');
    }
  };

  const handleLogout = async () => {
  Alert.alert(
    'Kijelentkezés',
    'Biztosan ki szeretnél jelentkezni?',
    [
      { text: 'Mégse', style: 'cancel' },
      {
        text: 'Kijelentkezés',
        style: 'destructive',
        onPress: async () => {
          await logout();
          setLoggedIn(false);
          setEmail('');
          
          Alert.alert('Siker', 'Sikeresen kijelentkeztél!', [
            {
              text: 'OK',
              onPress: () => {
                // Navigálás a Főoldalra
                navigation.navigate('Főoldal');
              }
            }
          ]);
        },
      },
    ]
  );
};

  if (!loggedIn) {
    return (
      <SafeAreaView style={styles.container}>
        <View style={styles.content}>
          <Text style={styles.emoji}>🔒</Text>
          <Text style={styles.title}>Profil</Text>
          <Text style={styles.subtitle}>Nem vagy bejelentkezve</Text>

          <TouchableOpacity
            style={styles.button}
            onPress={() => navigation.navigate('Belépés')}
          >
            <Text style={styles.buttonText}>Bejelentkezés</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView contentContainerStyle={styles.content}>
        <Text style={styles.emoji}>👤</Text>
        <Text style={styles.title}>Profil</Text>

        <View style={styles.card}>
          <Text style={styles.label}>Bejelentkezve mint:</Text>
          <Text style={styles.email}>{email}</Text>

          <View style={styles.divider} />

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => navigation.navigate('Kedvencek')}
          >
            <Text style={styles.menuItemText}>⭐ Kedvenc városok</Text>
            <Text style={styles.menuItemArrow}>→</Text>
          </TouchableOpacity>

          <View style={styles.divider} />

          <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
            <Text style={styles.logoutButtonText}>🚪 Kijelentkezés</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.infoBox}>
          <Text style={styles.infoText}>
            ℹ️ További beállításokat a webes felületen találsz
          </Text>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f0f9ff' },
  content: { flexGrow: 1, padding: 20, justifyContent: 'center' },
  emoji: { fontSize: 64, textAlign: 'center', marginBottom: 16 },
  title: { fontSize: 32, fontWeight: 'bold', color: '#1e3a8a', textAlign: 'center', marginBottom: 8 },
  subtitle: { fontSize: 16, color: '#64748b', textAlign: 'center', marginBottom: 32 },
  card: { backgroundColor: '#fff', borderRadius: 20, padding: 24, elevation: 4 },
  label: { fontSize: 14, fontWeight: '600', color: '#64748b', marginBottom: 8 },
  email: { fontSize: 20, fontWeight: 'bold', color: '#1e3a8a', marginBottom: 16 },
  divider: { height: 1, backgroundColor: '#e2e8f0', marginVertical: 16 },
  menuItem: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingVertical: 12 },
  menuItemText: { fontSize: 16, fontWeight: '600', color: '#1e293b' },
  menuItemArrow: { fontSize: 20, color: '#3b82f6' },
  button: { backgroundColor: '#3b82f6', height: 56, borderRadius: 12, justifyContent: 'center', alignItems: 'center', marginBottom: 20 },
  buttonText: { color: '#fff', fontSize: 18, fontWeight: 'bold' },
  logoutButton: { backgroundColor: '#ef4444', height: 56, borderRadius: 12, justifyContent: 'center', alignItems: 'center', marginTop: 8 },
  logoutButtonText: { color: '#fff', fontSize: 18, fontWeight: 'bold' },
  infoBox: { backgroundColor: '#dbeafe', borderRadius: 12, padding: 16, marginTop: 24, borderLeftWidth: 4, borderLeftColor: '#3b82f6' },
  infoText: { fontSize: 14, color: '#1e3a8a' },
});