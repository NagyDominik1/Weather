import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  SafeAreaView,
  ScrollView,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { Picker } from '@react-native-picker/picker';
import { isLoggedIn } from '../services/api';

export default function ArchiveScreen({ navigation }) {
  const [loggedIn, setLoggedIn] = useState(false);
  const [loading, setLoading] = useState(true);
  const [selectedCity, setSelectedCity] = useState('');
  const [selectedDate, setSelectedDate] = useState('');
  const [archiveData, setArchiveData] = useState([]);

  // Város lista (bővíthető API-ból is)
  const cities = [
    { id: '', name: 'Válassz várost...' },
    { id: 1, name: 'Budapest' },
    { id: 2, name: 'Szabadka' },
    { id: 3, name: 'Újvidék' },
    { id: 4, name: 'Belgrád' },
  ];

  // Utolsó 30 nap generálása
  const generateDates = () => {
    const dates = [{ value: '', label: 'Válassz dátumot...' }];
    const today = new Date();
    
    for (let i = 0; i < 30; i++) {
      const date = new Date(today);
      date.setDate(date.getDate() - i);
      const dateStr = date.toISOString().split('T')[0];
      const labelStr = date.toLocaleDateString('hu-HU');
      dates.push({ value: dateStr, label: labelStr });
    }
    
    return dates;
  };

  const dates = generateDates();

  useEffect(() => {
    checkLoginStatus();
  }, []);

  useEffect(() => {
    const unsubscribe = navigation.addListener('focus', () => {
      checkLoginStatus();
    });
    return unsubscribe;
  }, [navigation]);

  const checkLoginStatus = async () => {
    const status = await isLoggedIn();
    setLoggedIn(status);
    setLoading(false);
  };

  const handleSearch = async () => {
    if (!selectedCity && !selectedDate) {
      Alert.alert('Válassz szűrőt', 'Válassz várost vagy dátumot a kereséshez!');
      return;
    }

    setLoading(true);

    // TODO: API hívás az archívum adatokért
    // const result = await getArchive(selectedCity, selectedDate);
    
    // MOCK adat példa
    setTimeout(() => {
      const mockData = [
        {
          id: 1,
          city_name: cities.find(c => c.id == selectedCity)?.name || 'Budapest',
          dt: new Date().toISOString(),
          temp: 18,
          humidity: 65,
          wind_speed: 3.2,
          description: 'Tiszta égbolt',
        },
        {
          id: 2,
          city_name: cities.find(c => c.id == selectedCity)?.name || 'Budapest',
          dt: new Date(Date.now() - 86400000).toISOString(),
          temp: 16,
          humidity: 72,
          wind_speed: 2.8,
          description: 'Felhős',
        },
      ];
      
      setArchiveData(mockData);
      setLoading(false);
    }, 1000);
  };

  const handleReset = () => {
    setSelectedCity('');
    setSelectedDate('');
    setArchiveData([]);
  };

  if (loading) {
    return (
      <SafeAreaView style={styles.container}>
        <View style={styles.centerContent}>
          <ActivityIndicator size="large" color="#3b82f6" />
          <Text style={styles.loadingText}>Betöltés...</Text>
        </View>
      </SafeAreaView>
    );
  }

  if (!loggedIn) {
    return (
      <SafeAreaView style={styles.container}>
        <View style={styles.centerContent}>
          <Text style={styles.emptyEmoji}>🔒</Text>
          <Text style={styles.emptyTitle}>Bejelentkezés szükséges</Text>
          <Text style={styles.emptyText}>
            Az archívum csak bejelentkezett felhasználók számára elérhető!
          </Text>
          
          <TouchableOpacity
            style={styles.loginButton}
            onPress={() => navigation.navigate('Belépés')}
          >
            <Text style={styles.loginButtonText}>Bejelentkezés</Text>
          </TouchableOpacity>
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView contentContainerStyle={styles.content}>
        <Text style={styles.title}>📚 Időjárás archívum</Text>

        {/* SZŰRŐK */}
        <View style={styles.filterCard}>
          <Text style={styles.filterTitle}>Szűrők</Text>

          {/* VÁROS VÁLASZTÓ */}
          <View style={styles.filterGroup}>
            <Text style={styles.label}>Város:</Text>
            <View style={styles.pickerContainer}>
              <Picker
                selectedValue={selectedCity}
                onValueChange={(value) => setSelectedCity(value)}
                style={styles.picker}
              >
                {cities.map((city) => (
                  <Picker.Item key={city.id} label={city.name} value={city.id} />
                ))}
              </Picker>
            </View>
          </View>

          {/* DÁTUM VÁLASZTÓ */}
          <View style={styles.filterGroup}>
            <Text style={styles.label}>Dátum:</Text>
            <View style={styles.pickerContainer}>
              <Picker
                selectedValue={selectedDate}
                onValueChange={(value) => setSelectedDate(value)}
                style={styles.picker}
              >
                {dates.map((date, index) => (
                  <Picker.Item key={index} label={date.label} value={date.value} />
                ))}
              </Picker>
            </View>
          </View>

          {/* GOMBOK */}
          <View style={styles.buttonRow}>
            <TouchableOpacity
              style={[styles.button, styles.resetButton]}
              onPress={handleReset}
            >
              <Text style={styles.resetButtonText}>Törlés</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={[styles.button, styles.searchButton]}
              onPress={handleSearch}
            >
              <Text style={styles.searchButtonText}>🔍 Keresés</Text>
            </TouchableOpacity>
          </View>
        </View>

        {/* EREDMÉNYEK */}
        {archiveData.length > 0 && (
          <View style={styles.resultsContainer}>
            <Text style={styles.resultsTitle}>Találatok ({archiveData.length})</Text>

            {archiveData.map((item) => (
              <View key={item.id} style={styles.resultCard}>
                <View style={styles.resultHeader}>
                  <Text style={styles.resultCity}>{item.city_name}</Text>
                  <Text style={styles.resultDate}>
                    {new Date(item.dt).toLocaleDateString('hu-HU', {
                      year: 'numeric',
                      month: 'long',
                      day: 'numeric',
                    })}
                  </Text>
                </View>

                <View style={styles.resultDetails}>
                  <View style={styles.resultItem}>
                    <Text style={styles.resultLabel}>🌡️ Hőmérséklet:</Text>
                    <Text style={styles.resultValue}>{item.temp}°C</Text>
                  </View>

                  <View style={styles.resultItem}>
                    <Text style={styles.resultLabel}>💧 Páratartalom:</Text>
                    <Text style={styles.resultValue}>{item.humidity}%</Text>
                  </View>

                  <View style={styles.resultItem}>
                    <Text style={styles.resultLabel}>💨 Szél:</Text>
                    <Text style={styles.resultValue}>{item.wind_speed} m/s</Text>
                  </View>

                  <View style={styles.resultItem}>
                    <Text style={styles.resultLabel}>☁️ Leírás:</Text>
                    <Text style={styles.resultValue}>{item.description}</Text>
                  </View>
                </View>
              </View>
            ))}
          </View>
        )}

        {archiveData.length === 0 && (selectedCity || selectedDate) && !loading && (
          <View style={styles.noResults}>
            <Text style={styles.noResultsEmoji}>🔍</Text>
            <Text style={styles.noResultsText}>Nincs találat a keresési feltételeknek megfelelően</Text>
          </View>
        )}

        <View style={styles.infoBox}>
          <Text style={styles.infoText}>
            💡 Válassz várost és/vagy dátumot az időjárási előzmények megtekintéséhez!
          </Text>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f0f9ff' },
  content: { padding: 20 },
  centerContent: { flex: 1, justifyContent: 'center', alignItems: 'center', padding: 40 },
  loadingText: { marginTop: 12, fontSize: 16, color: '#64748b' },
  title: { fontSize: 28, fontWeight: 'bold', color: '#1e3a8a', marginBottom: 20 },
  filterCard: { backgroundColor: '#fff', borderRadius: 16, padding: 20, marginBottom: 20, elevation: 3 },
  filterTitle: { fontSize: 18, fontWeight: 'bold', color: '#1e3a8a', marginBottom: 16 },
  filterGroup: { marginBottom: 16 },
  label: { fontSize: 14, fontWeight: '600', color: '#64748b', marginBottom: 8 },
  pickerContainer: { backgroundColor: '#f8fafc', borderRadius: 12, borderWidth: 1, borderColor: '#e2e8f0' },
  picker: { height: 50 },
  buttonRow: { flexDirection: 'row', gap: 12, marginTop: 8 },
  button: { flex: 1, height: 48, borderRadius: 12, justifyContent: 'center', alignItems: 'center' },
  resetButton: { backgroundColor: '#f1f5f9', borderWidth: 1, borderColor: '#cbd5e1' },
  resetButtonText: { color: '#475569', fontSize: 16, fontWeight: '600' },
  searchButton: { backgroundColor: '#3b82f6' },
  searchButtonText: { color: '#fff', fontSize: 16, fontWeight: 'bold' },
  resultsContainer: { marginBottom: 20 },
  resultsTitle: { fontSize: 18, fontWeight: 'bold', color: '#1e3a8a', marginBottom: 12 },
  resultCard: { backgroundColor: '#fff', borderRadius: 12, padding: 16, marginBottom: 12, elevation: 2 },
  resultHeader: { marginBottom: 12, borderBottomWidth: 1, borderBottomColor: '#f1f5f9', paddingBottom: 8 },
  resultCity: { fontSize: 18, fontWeight: 'bold', color: '#1e3a8a' },
  resultDate: { fontSize: 14, color: '#64748b', marginTop: 4 },
  resultDetails: { gap: 8 },
  resultItem: { flexDirection: 'row', justifyContent: 'space-between', paddingVertical: 4 },
  resultLabel: { fontSize: 14, color: '#64748b' },
  resultValue: { fontSize: 14, fontWeight: '600', color: '#1e293b' },
  noResults: { alignItems: 'center', paddingVertical: 40 },
  noResultsEmoji: { fontSize: 48, marginBottom: 12 },
  noResultsText: { fontSize: 16, color: '#64748b', textAlign: 'center' },
  emptyEmoji: { fontSize: 64, marginBottom: 16 },
  emptyTitle: { fontSize: 20, fontWeight: 'bold', color: '#1e3a8a', marginBottom: 8, textAlign: 'center' },
  emptyText: { fontSize: 16, color: '#64748b', textAlign: 'center', marginBottom: 24, lineHeight: 24 },
  loginButton: { backgroundColor: '#3b82f6', paddingHorizontal: 32, paddingVertical: 16, borderRadius: 12 },
  loginButtonText: { color: '#fff', fontSize: 18, fontWeight: 'bold' },
  infoBox: { backgroundColor: '#dbeafe', borderRadius: 12, padding: 16, marginTop: 20, borderLeftWidth: 4, borderLeftColor: '#3b82f6' },
  infoText: { fontSize: 14, color: '#1e3a8a' },
});