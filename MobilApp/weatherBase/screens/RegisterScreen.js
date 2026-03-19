import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  SafeAreaView,
  Alert,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { register } from '../services/api';

export default function RegisterScreen({ navigation }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleRegister = async () => {
    // Validáció
    if (!email || !password || !confirmPassword) {
      Alert.alert('Hiba', 'Töltsd ki az összes mezőt!');
      return;
    }

    if (password !== confirmPassword) {
      Alert.alert('Hiba', 'A jelszavak nem egyeznek!');
      return;
    }

    if (password.length < 6) {
      Alert.alert('Hiba', 'A jelszónak legalább 6 karakter hosszúnak kell lennie!');
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      Alert.alert('Hiba', 'Érvénytelen email cím!');
      return;
    }

    setLoading(true);

    const result = await register(email, password);

    setLoading(false);

    if (result.success) {
      Alert.alert(
        'Siker!', 
        'Regisztráció sikeres! Elküldtünk egy aktiválási linket az email címedre.',
        [
          { text: 'OK', onPress: () => navigation.navigate('Login') }
        ]
      );
    } else {
      Alert.alert('Hiba', 'Regisztráció sikertelen: ' + result.error);
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <KeyboardAvoidingView 
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        style={styles.content}
      >
        <Text style={styles.emoji}>✨</Text>
        <Text style={styles.title}>Regisztráció</Text>

        <View style={styles.card}>
          <Text style={styles.label}>Email cím</Text>
          <TextInput
            style={styles.input}
            placeholder="email@example.com"
            value={email}
            onChangeText={setEmail}
            keyboardType="email-address"
            autoCapitalize="none"
            autoCorrect={false}
          />

          <Text style={styles.label}>Jelszó</Text>
          <TextInput
            style={styles.input}
            placeholder="Min. 6 karakter"
            value={password}
            onChangeText={setPassword}
            secureTextEntry
          />

          <Text style={styles.label}>Jelszó megerősítése</Text>
          <TextInput
            style={styles.input}
            placeholder="Jelszó újra"
            value={confirmPassword}
            onChangeText={setConfirmPassword}
            secureTextEntry
          />

          <TouchableOpacity
            style={[styles.button, loading && styles.buttonDisabled]}
            onPress={handleRegister}
            disabled={loading}
          >
            {loading ? (
              <ActivityIndicator color="#fff" />
            ) : (
              <Text style={styles.buttonText}>Regisztráció</Text>
            )}
          </TouchableOpacity>

          <TouchableOpacity 
            style={styles.linkButton}
            onPress={() => navigation.navigate('Login')}
          >
            <Text style={styles.linkText}>Van már fiókod? Bejelentkezés</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.infoBox}>
          <Text style={styles.infoText}>
            📧 Aktiválási linket küldünk az email címedre!
          </Text>
        </View>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f0f9ff' },
  content: { flex: 1, padding: 20, justifyContent: 'center' },
  emoji: { fontSize: 64, textAlign: 'center', marginBottom: 16 },
  title: { fontSize: 32, fontWeight: 'bold', color: '#1e3a8a', textAlign: 'center', marginBottom: 32 },
  card: { backgroundColor: '#fff', borderRadius: 20, padding: 24, shadowColor: '#000', shadowOffset: { width: 0, height: 2 }, shadowOpacity: 0.1, shadowRadius: 8, elevation: 4 },
  label: { fontSize: 14, fontWeight: '600', color: '#64748b', marginBottom: 8 },
  input: { height: 56, borderWidth: 2, borderColor: '#e2e8f0', borderRadius: 12, paddingHorizontal: 16, fontSize: 16, marginBottom: 16, backgroundColor: '#f8fafc' },
  button: { backgroundColor: '#3b82f6', height: 56, borderRadius: 12, justifyContent: 'center', alignItems: 'center', marginTop: 8 },
  buttonDisabled: { backgroundColor: '#94a3b8' },
  buttonText: { color: '#fff', fontSize: 18, fontWeight: 'bold' },
  linkButton: { marginTop: 16, alignItems: 'center' },
  linkText: { color: '#3b82f6', fontSize: 14, fontWeight: '600' },
  infoBox: { backgroundColor: '#dbeafe', borderRadius: 12, padding: 16, marginTop: 24, borderLeftWidth: 4, borderLeftColor: '#3b82f6' },
  infoText: { fontSize: 14, color: '#1e3a8a' },
});