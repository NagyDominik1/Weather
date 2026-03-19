import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ActivityIndicator,
  ScrollView,
  SafeAreaView,
  Alert,
  TouchableOpacity,
} from 'react-native';
import { getWeather, isLoggedIn, addFavorite, removeFavorite, isFavorite } from '../services/api';

export default function WeatherScreen({ route, navigation }) {
  const { cityName } = route.params;
  const [loading, setLoading] = useState(true);
  const [weatherData, setWeatherData] = useState(null);
  const [loggedIn, setLoggedIn] = useState(false);
  const [favorite, setFavorite] = useState(false);
  const [cityId, setCityId] = useState(null);

  useEffect(() => {
    const init = async () => {
      await checkLoginStatus();
      await fetchWeather();
    };
    init();
  }, []);

  const checkLoginStatus = async () => {
    const status = await isLoggedIn();
    setLoggedIn(status);
  };

  const fetchWeather = async () => {
    setLoading(true);
    const result = await getWeather(cityName);

    if (result.success && result.data) {
      setWeatherData(result.data);
      setCityId(result.data.city_id);
      if (result.data.city_id) {
        checkIfFavorite(result.data.city_id);
      }
    } else {
      Alert.alert('Hiba', 'Nem sikerült lekérni az adatokat.');
    }
    setLoading(false);
  };

  const checkIfFavorite = async (id) => {
    if (!loggedIn) return;
    const isFav = await isFavorite(id);
    setFavorite(isFav);
  };

  // Segédfüggvény az időpontok formázásához (napkelte/napnyugta)
  const formatTime = (timestamp) => {
    if (!timestamp) return '--:--';
    const date = new Date(timestamp * 1000);
    return date.toLocaleTimeString('hu-HU', { hour: '2-digit', minute: '2-digit' });
  };

  const toggleFavorite = async () => {
    if (!loggedIn) {
      Alert.alert('Belépés szükséges', 'A funkcióhoz jelentkezz be!', [
        { text: 'Mégse' },
        { text: 'Belépés', onPress: () => navigation.navigate('Login') }
      ]);
      return;
    }
    if (favorite) {
      const res = await removeFavorite(cityId);
      if (res.success) setFavorite(false);
    } else {
      const res = await addFavorite(cityId);
      if (res.success) setFavorite(true);
    }
  };

  if (loading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  const data = weatherData || {};

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView contentContainerStyle={styles.content}>
        
        {/* HEADER */}
        <View style={styles.header}>
          <View>
            <Text style={styles.cityName}>{data.name || cityName}</Text>
            <Text style={styles.description}>{data.weather?.[0]?.description || 'Nincs leírás'}</Text>
          </View>
          <TouchableOpacity style={styles.favoriteButton} onPress={toggleFavorite}>
            <Text style={styles.favoriteIcon}>{favorite ? '⭐' : '☆'}</Text>
          </TouchableOpacity>
        </View>

        {/* FŐ HŐMÉRSÉKLET KÁRTYA */}
        <View style={styles.tempCard}>
          <Text style={styles.tempIcon}>🌤️</Text>
          <Text style={styles.temp}>{Math.round(data.main?.temp || 0)}°C</Text>
          <View style={styles.minMaxRow}>
            <Text style={styles.minMaxText}>Min: {Math.round(data.main?.temp_min || 0)}°C</Text>
            <Text style={[styles.minMaxText, { marginLeft: 15 }]}>Max: {Math.round(data.main?.temp_max || 0)}°C</Text>
          </View>
        </View>

        {/* RÉSZLETES ADATOK RÁCS (Grid-szerű elrendezés) */}
        <View style={styles.grid}>
          <View style={styles.infoSquare}>
            <Text style={styles.infoLabel}>🌡️ Hőérzet</Text>
            <Text style={styles.infoValue}>{Math.round(data.main?.feels_like || 0)}°C</Text>
          </View>
          <View style={styles.infoSquare}>
            <Text style={styles.infoLabel}>💧 Pára</Text>
            <Text style={styles.infoValue}>{data.main?.humidity || 0}%</Text>
          </View>
          <View style={styles.infoSquare}>
            <Text style={styles.infoLabel}>⏲️ Nyomás</Text>
            <Text style={styles.infoValue}>{data.main?.pressure || 0} hPa</Text>
          </View>
          <View style={styles.infoSquare}>
            <Text style={styles.infoLabel}>💨 Szél</Text>
            <Text style={styles.infoValue}>{data.wind?.speed || 0} m/s</Text>
          </View>
          <View style={styles.infoSquare}>
            <Text style={styles.infoLabel}>🌅 Napkelte</Text>
            <Text style={styles.infoValueSmall}>{formatTime(data.sys?.sunrise)}</Text>
          </View>
          <View style={styles.infoSquare}>
            <Text style={styles.infoLabel}>🌇 Napnyugta</Text>
            <Text style={styles.infoValueSmall}>{formatTime(data.sys?.sunset)}</Text>
          </View>
        </View>

        {/* LÁTHATÓSÁG ÉS EGYEBEK */}
        <View style={styles.detailsCard}>
           <View style={styles.detailRow}>
              <Text style={styles.detailLabel}>👁️ Láthatóság</Text>
              <Text style={styles.detailValue}>{(data.visibility || 0) / 1000} km</Text>
           </View>
           <View style={styles.detailRow}>
              <Text style={styles.detailLabel}>☁️ Felhőzet</Text>
              <Text style={styles.detailValue}>{data.clouds?.all || 0}%</Text>
           </View>
        </View>

      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f0f9ff' },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  content: { padding: 20 },
  header: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 20 },
  cityName: { fontSize: 32, fontWeight: 'bold', color: '#1e3a8a' },
  description: { fontSize: 18, color: '#64748b', textTransform: 'capitalize' },
  favoriteButton: { width: 50, height: 50, backgroundColor: '#fff', borderRadius: 25, justifyContent: 'center', alignItems: 'center', elevation: 3 },
  favoriteIcon: { fontSize: 28 },
  tempCard: { backgroundColor: '#fff', borderRadius: 20, padding: 25, alignItems: 'center', marginBottom: 20, elevation: 5 },
  tempIcon: { fontSize: 60, marginBottom: 10 },
  temp: { fontSize: 64, fontWeight: 'bold', color: '#1e3a8a' },
  minMaxRow: { flexDirection: 'row', marginTop: 5 },
  minMaxText: { fontSize: 16, color: '#64748b' },
  grid: { flexDirection: 'row', flexWrap: 'wrap', justifyContent: 'space-between' },
  infoSquare: { backgroundColor: '#fff', width: '31%', padding: 15, borderRadius: 15, alignItems: 'center', marginBottom: 10, elevation: 2 },
  infoLabel: { fontSize: 12, color: '#64748b', marginBottom: 5, textAlign: 'center' },
  infoValue: { fontSize: 18, fontWeight: 'bold', color: '#1e3a8a' },
  infoValueSmall: { fontSize: 16, fontWeight: 'bold', color: '#1e3a8a' },
  detailsCard: { backgroundColor: '#fff', borderRadius: 20, padding: 20, marginTop: 10, elevation: 3 },
  detailRow: { flexDirection: 'row', justifyContent: 'space-between', paddingVertical: 10, borderBottomWidth: 1, borderBottomColor: '#f1f5f9' },
  detailLabel: { fontSize: 16, color: '#64748b' },
  detailValue: { fontSize: 16, fontWeight: 'bold', color: '#1e3a8a' },
});