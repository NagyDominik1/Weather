import React from 'react';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Ionicons } from '@expo/vector-icons';

import HomeScreen from './screens/HomeScreen';
import FavoritesScreen from './screens/FavoritesScreen';
import ArchiveScreen from './screens/ArchiveScreen';
import LoginScreen from './screens/LoginScreen';

const Tab = createBottomTabNavigator();

export default function AppNavigator() {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        tabBarIcon: ({ color, size }) => {
          let iconName;
          if (route.name === 'Főoldal') iconName = 'home';
          else if (route.name === 'Kedvencek') iconName = 'star';
          else if (route.name === 'Archívum') iconName = 'archive';
          else if (route.name === 'Belépés') iconName = 'person';
          
          return <Ionicons name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: '#3b82f6',
        tabBarInactiveTintColor: 'gray',
        headerShown: false,
      })}
    >
      <Tab.Screen name="Főoldal" component={HomeScreen} />
      <Tab.Screen name="Kedvencek" component={FavoritesScreen} />
      <Tab.Screen name="Archívum" component={ArchiveScreen} />
      <Tab.Screen name="Belépés" component={LoginScreen} />
    </Tab.Navigator>
  );
}