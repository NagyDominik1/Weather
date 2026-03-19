import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Alert,
  ActivityIndicator,
  SafeAreaView,
  KeyboardAvoidingView,
  Platform,
  ScrollView
} from 'react-native';
import { getWeather } from '../services/api'; // Az api.js importálása

export default function HomeScreen({ navigation }) {
  const [cityName, setCityName] = useState('');
  const [loading, setLoading] = useState(false);

const handleSearch = async () => {
  if (!cityName.trim()) {
    Alert.alert('Hiba', 'Kérlek, adj meg egy városnevet!');
    return;
  }

  console.log('🔍 Searching for city:', cityName.trim()); // DEBUG

  setLoading(true);
  
  // Egyszerűen navigálunk a városnévvel
  // A WeatherScreen fogja lekérni az időjárást
  navigation.navigate('Weather', { cityName: cityName.trim() });
  
  setLoading(false);
  setCityName(''); // Input mező törlése
};

  return (
    <SafeAreaView style={styles.container}>
      <KeyboardAvoidingView 
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        style={{ flex: 1 }}
      >
        <ScrollView contentContainerStyle={styles.scrollContent}>
          <View style={styles.header}>
            <Text style={styles.emoji}>☀️</Text>
            <Text style={styles.title}>WeatherBase</Text>
            <Text style={styles.subtitle}>Fedezd fel a pontos időjárást!</Text>
          </View>

          <View style={styles.card}>
            <Text style={styles.label}>Helyszín keresése</Text>
            <View style={styles.inputContainer}>
              <TextInput
                style={styles.input}
                placeholder="Város neve (pl. Szabadka)..."
                placeholderTextColor="#94a3b8"
                value={cityName}
                onChangeText={setCityName}
                onSubmitEditing={handleSearch}
                autoCapitalize="words"
                returnKeyType="search"
              />
            </View>
            
            <TouchableOpacity
              style={[styles.button, loading && styles.buttonDisabled]}
              onPress={handleSearch}
              disabled={loading}
            >
              {loading ? (
                <ActivityIndicator color="#fff" />
              ) : (
                <Text style={styles.buttonText}>Látni akarom</Text>
              )}
            </TouchableOpacity>
          </View>

          {/* Statisztikai kártya - A dokumentáció 5. fejezetéhez jól mutat */}
          <View style={styles.infoBox}>
            <Text style={styles.infoText}>
              💡 Tipp: Adj hozzá városokat a kedvenceidhez, hogy gyorsabban elérd őket!
            </Text>
          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f0f9ff',
  },
  scrollContent: {
    flexGrow: 1,
    padding: 24,
    justifyContent: 'center',
  },
  header: {
    alignItems: 'center',
    marginBottom: 40,
  },
  emoji: {
    fontSize: 72,
    marginBottom: 10,
  },
  title: {
    fontSize: 40,
    fontWeight: '900',
    color: '#1e3a8a',
    letterSpacing: -1,
  },
  subtitle: {
    fontSize: 16,
    color: '#64748b',
    fontWeight: '500',
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 24,
    padding: 24,
    shadowColor: '#1e293b',
    shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.1,
    shadowRadius: 20,
    elevation: 5,
  },
  label: {
    fontSize: 14,
    fontWeight: '700',
    color: '#334155',
    marginBottom: 12,
    textTransform: 'uppercase',
  },
  inputContainer: {
    marginBottom: 20,
  },
  input: {
    height: 60,
    backgroundColor: '#f8fafc',
    borderWidth: 1.5,
    borderColor: '#e2e8f0',
    borderRadius: 16,
    paddingHorizontal: 20,
    fontSize: 16,
    color: '#1e293b',
  },
  button: {
    backgroundColor: '#2563eb',
    height: 60,
    borderRadius: 16,
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#2563eb',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 2,
  },
  buttonDisabled: {
    backgroundColor: '#94a3b8',
  },
  buttonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
  },
  infoBox: {
    marginTop: 30,
    padding: 16,
    backgroundColor: '#dbeafe',
    borderRadius: 12,
    borderLeftWidth: 4,
    borderLeftColor: '#3b82f6',
  },
  infoText: {
    color: '#1e40af',
    fontSize: 14,
    lineHeight: 20,
  },
});