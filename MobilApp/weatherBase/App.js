import React, { useState, useEffect } from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { Ionicons } from '@expo/vector-icons';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { isLoggedIn } from './services/api';

import HomeScreen from './screens/HomeScreen';
import WeatherScreen from './screens/WeatherScreen';
import FavoritesScreen from './screens/FavoritesScreen';
import LoginScreen from './screens/LoginScreen';
import ProfileScreen from './screens/ProfileScreen';
import ArchiveScreen from './screens/ArchiveScreen'; // Importálva

const Tab = createBottomTabNavigator();
const Stack = createNativeStackNavigator();

function HomeStack() {
  return (
    <Stack.Navigator screenOptions={{ headerShown: false }}>
      <Stack.Screen name="HomeMain" component={HomeScreen} />
      <Stack.Screen 
        name="Weather" 
        component={WeatherScreen} 
        options={{ 
          headerShown: true,
          title: 'Időjárás részletek',
          headerStyle: { backgroundColor: '#3b82f6' },
          headerTintColor: '#fff'
        }} 
      />
    </Stack.Navigator>
  );
}

function MainTabs() {
  const [loggedIn, setLoggedIn] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    checkLoginStatus();
  }, []);

  const checkLoginStatus = async () => {
    const status = await isLoggedIn();
    console.log('📊 Login status:', status);
    setLoggedIn(status);
    setLoading(false);
  };

  if (loading) return null;

  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        tabBarIcon: ({ focused, color, size }) => {
          let iconName;
          if (route.name === 'Főoldal') iconName = focused ? 'home' : 'home-outline';
          else if (route.name === 'Kedvencek') iconName = focused ? 'star' : 'star-outline';
          else if (route.name === 'Archívum') iconName = focused ? 'archive' : 'archive-outline'; // Új ikon
          else if (route.name === 'Belépés') iconName = focused ? 'log-in' : 'log-in-outline';
          else if (route.name === 'Profil') iconName = focused ? 'person-circle' : 'person-circle-outline';

          return <Ionicons name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: '#3b82f6',
        tabBarInactiveTintColor: 'gray',
        tabBarStyle: { 
          height: 95,
          paddingBottom: 35,
          paddingTop: 10,
          backgroundColor: '#ffffff',
          borderTopWidth: 1,
          borderTopColor: '#e2e8f0',
        },
        headerShown: false,
      })}
      screenListeners={{
        state: () => { checkLoginStatus(); },
      }}
    >
      <Tab.Screen name="Főoldal" component={HomeStack} />
      <Tab.Screen name="Kedvencek" component={FavoritesScreen} />
      
      {/* ÚJ ARCHÍVUM TAB */}
      <Tab.Screen name="Archívum" component={ArchiveScreen} />
      
      {loggedIn ? (
        <Tab.Screen name="Profil" component={ProfileScreen} />
      ) : (
        <Tab.Screen name="Belépés" component={LoginScreen} />
      )}
    </Tab.Navigator>
  );
}

export default function App() {
  return (
    <SafeAreaProvider>
      <NavigationContainer>
        <MainTabs />
      </NavigationContainer>
    </SafeAreaProvider>
  );
}