import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  SafeAreaView,
  TouchableOpacity,
  ScrollView,
  ActivityIndicator,
  Alert,
  RefreshControl,
} from 'react-native';
import { getFavorites, isLoggedIn, removeFavorite } from '../services/api';

export default function FavoritesScreen({ navigation }) {
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [loggedIn, setLoggedIn] = useState(false);

  useEffect(() => {
    loadFavorites();
  }, []);

  // Amikor a screen fókuszba kerül (pl. tab váltás után)
  useEffect(() => {
    const unsubscribe = navigation.addListener('focus', () => {
      loadFavorites();
    });
    return unsubscribe;
  }, [navigation]);

  const loadFavorites = async () => {
    setLoading(true);

    // Ellenőrizzük bejelentkezés státuszt
    const status = await isLoggedIn();
    setLoggedIn(status);

    if (!status) {
      setFavorites([]);
      setLoading(false);
      return;
    }

    // API hívás
    const result = await getFavorites();

    if (result.success) {
      console.log('✅ Favorites loaded:', result.data);
      setFavorites(result.data || []);
    } else {
      console.error('❌ Failed to load favorites:', result.error);
      setFavorites([]);
    }

    setLoading(false);
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadFavorites();
    setRefreshing(false);
  };

  const handleRemoveFavorite = async (cityId, cityName) => {
    Alert.alert(
      'Törlés megerősítése',
      `Biztosan eltávolítod "${cityName}" várost a kedvencek közül?`,
      [
        { text: 'Mégse', style: 'cancel' },
        {
          text: 'Törlés',
          style: 'destructive',
          onPress: async () => {
            const result = await removeFavorite(cityId);
            
            if (result.success) {
              Alert.alert('Siker', `${cityName} eltávolítva!`);
              loadFavorites(); // Frissítjük a listát
            } else {
              Alert.alert('Hiba', 'Nem sikerült eltávolítani a várost.');
            }
          },
        },
      ]
    );
  };

  // LOADING ÁLLAPOT
  if (loading) {
    return (
      <SafeAreaView style={styles.container}>
        <View style={styles.centerContent}>
          <ActivityIndicator size="large" color="#3b82f6" />
          <Text style={styles.loadingText}>Kedvencek betöltése...</Text>
        </View>
      </SafeAreaView>
    );
  }

  // NINCS BEJELENTKEZVE
  if (!loggedIn) {
    return (
      <SafeAreaView style={styles.container}>
        <View style={styles.centerContent}>
          <Text style={styles.emptyEmoji}>🔒</Text>
          <Text style={styles.emptyTitle}>Bejelentkezés szükséges</Text>
          <Text style={styles.emptyText}>
            Jelentkezz be, hogy megnézd a kedvenc városaidat!
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

  // ÜRES LISTA
  if (favorites.length === 0) {
    return (
      <SafeAreaView style={styles.container}>
        <ScrollView
          contentContainerStyle={styles.centerContent}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
          }
        >
          <Text style={styles.emptyEmoji}>📭</Text>
          <Text style={styles.emptyTitle}>Még nincs kedvenc városod!</Text>
          <Text style={styles.emptyText}>
            Keress rá egy városra és add hozzá a kedvencekhez a csillag gombbal!
          </Text>

          <TouchableOpacity
            style={styles.searchButton}
            onPress={() => navigation.navigate('Főoldal')}
          >
            <Text style={styles.searchButtonText}>🔍 Város keresése</Text>
          </TouchableOpacity>
        </ScrollView>
      </SafeAreaView>
    );
  }

  // KEDVENCEK LISTÁJA
  return (
    <SafeAreaView style={styles.container}>
      <ScrollView
        contentContainerStyle={styles.content}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
      >
        <Text style={styles.title}>⭐ Kedvenc városaid ({favorites.length})</Text>

        {favorites.map((city) => (
          <View key={city.id} style={styles.card}>
            <TouchableOpacity
              style={styles.cardContent}
              onPress={() => {
                console.log('🏙️ Opening weather for:', city.city_name);
                navigation.navigate('Főoldal', {
                  screen: 'Weather',
                  params: { cityName: city.city_name }
                });
              }}
            >
              <View>
                <Text style={styles.cityName}>{city.city_name}</Text>
                {city.created_at && (
                  <Text style={styles.dateText}>
                    Hozzáadva: {new Date(city.created_at).toLocaleDateString('hu-HU')}
                  </Text>
                )}
              </View>
              <Text style={styles.arrow}>→</Text>
            </TouchableOpacity>

            <TouchableOpacity
              style={styles.deleteButton}
              onPress={() => handleRemoveFavorite(city.id, city.city_name)}
            >
              <Text style={styles.deleteIcon}>🗑️</Text>
            </TouchableOpacity>
          </View>
        ))}

        <View style={styles.infoBox}>
          <Text style={styles.infoText}>
            💡 Húzd le a listát a frissítéshez!
          </Text>
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f0f9ff',
  },
  content: {
    padding: 20,
  },
  centerContent: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 40,
  },
  loadingText: {
    marginTop: 12,
    fontSize: 16,
    color: '#64748b',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginBottom: 20,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    marginBottom: 12,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 2,
    overflow: 'hidden',
  },
  cardContent: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 20,
  },
  cityName: {
    fontSize: 18,
    fontWeight: '600',
    color: '#1e293b',
    marginBottom: 4,
  },
  dateText: {
    fontSize: 12,
    color: '#94a3b8',
  },
  arrow: {
    fontSize: 20,
    color: '#3b82f6',
  },
  deleteButton: {
    position: 'absolute',
    right: 12,
    top: 12,
    padding: 8,
    backgroundColor: '#fee2e2',
    borderRadius: 8,
  },
  deleteIcon: {
    fontSize: 18,
  },
  emptyEmoji: {
    fontSize: 64,
    marginBottom: 16,
  },
  emptyTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#1e3a8a',
    marginBottom: 8,
    textAlign: 'center',
  },
  emptyText: {
    fontSize: 16,
    color: '#64748b',
    textAlign: 'center',
    marginBottom: 24,
    lineHeight: 24,
  },
  loginButton: {
    backgroundColor: '#3b82f6',
    paddingHorizontal: 32,
    paddingVertical: 16,
    borderRadius: 12,
  },
  loginButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
  },
  searchButton: {
    backgroundColor: '#3b82f6',
    paddingHorizontal: 32,
    paddingVertical: 16,
    borderRadius: 12,
  },
  searchButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
  },
  infoBox: {
    backgroundColor: '#dbeafe',
    borderRadius: 12,
    padding: 16,
    marginTop: 20,
    borderLeftWidth: 4,
    borderLeftColor: '#3b82f6',
  },
  infoText: {
    fontSize: 14,
    color: '#1e3a8a',
    textAlign: 'center',
  },
});