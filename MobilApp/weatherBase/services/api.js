import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

const BASE_URL = 'https://ee.stud.vts.su.ac.rs/iws-2025-hu/Projekt-iws/public';

const api = axios.create({
  baseURL: BASE_URL,
  timeout: 15000,
});

// ========== SESSION KEZELÉS ==========
const saveSession = async (sessionId) => {
  console.log('💾 Saving session:', sessionId);
  await AsyncStorage.setItem('php_session', sessionId);
};

const loadSession = async () => {
  const sessionId = await AsyncStorage.getItem('php_session');
  return sessionId;
};

// ========== REQUEST INTERCEPTOR - AUTOMATIKUS SESSION KÜLDÉS ==========
api.interceptors.request.use(
  async (config) => {
    const sessionId = await loadSession();
    if (sessionId) {
      config.headers['Cookie'] = `PHPSESSID=${sessionId}`;
      console.log('🍪 Sending session with request:', config.url);
    } else {
      console.log('⚠️ No session found for:', config.url);
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// ========== AUTH ==========
export const login = async (email, password) => {
  try {
    console.log('🔐 Login attempt:', email);

    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    const response = await api.post('/login', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
      maxRedirects: 0,
      validateStatus: (status) => status >= 200 && status < 400,
    });

    console.log('✅ Response status:', response.status);

    // Session cookie mentése
    const setCookie = response.headers['set-cookie'];
    if (setCookie) {
      let sessionId = null;
      
      if (Array.isArray(setCookie)) {
        const phpSession = setCookie.find(c => c.includes('PHPSESSID'));
        if (phpSession) {
          sessionId = phpSession.split(';')[0].split('=')[1];
        }
      } else if (typeof setCookie === 'string' && setCookie.includes('PHPSESSID')) {
        sessionId = setCookie.split(';')[0].split('=')[1];
      }

      if (sessionId) {
        console.log('🍪 Session ID extracted:', sessionId);
        await saveSession(sessionId);
      }
    }

    if (response.status === 200 || response.status === 302 || response.status === 303) {
      await AsyncStorage.setItem('user_email', email);
      await AsyncStorage.setItem('logged_in', 'true');
      console.log('✅ LOGIN SUCCESSFUL!');
      return { success: true, data: 'Login sikeres' };
    }

    return { success: false, error: 'Váratlan válasz' };

  } catch (error) {
    console.error('❌ Login error:', error.message);

    if (error.response) {
      const status = error.response.status;
      console.log('📊 Error response status:', status);

      if (status === 302 || status === 303) {
        const setCookie = error.response.headers['set-cookie'];
        if (setCookie) {
          let sessionId = null;
          
          if (Array.isArray(setCookie)) {
            const phpSession = setCookie.find(c => c.includes('PHPSESSID'));
            if (phpSession) {
              sessionId = phpSession.split(';')[0].split('=')[1];
            }
          }

          if (sessionId) {
            console.log('🍪 Session ID from redirect:', sessionId);
            await saveSession(sessionId);
          }
        }

        await AsyncStorage.setItem('user_email', email);
        await AsyncStorage.setItem('logged_in', 'true');
        console.log('✅ LOGIN SUCCESSFUL (via redirect)!');
        return { success: true, data: 'Login sikeres' };
      }
    }

    return { success: false, error: 'Hibás email vagy jelszó' };
  }
};

export const logout = async () => {
  await AsyncStorage.removeItem('php_session');
  await AsyncStorage.removeItem('user_email');
  await AsyncStorage.removeItem('logged_in');
  console.log('👋 Logged out, session cleared');
};

export const isLoggedIn = async () => {
  const loggedIn = await AsyncStorage.getItem('logged_in');
  return loggedIn === 'true';
};

// ========== WEATHER ==========
export const getWeather = async (cityName) => {
  try {
    console.log('🌤️ Fetching weather for:', cityName);
    
    const response = await api.get(`/api/weather?city_name=${encodeURIComponent(cityName)}`);
    
    console.log('✅ Weather response status:', response.status);
    console.log('📦 Weather data:', response.data);

    if (response.data && response.data.success) {
      return { 
        success: true, 
        data: {
          city_id: response.data.city_id,
          name: response.data.city_name,
          main: response.data.weather.main,
          wind: response.data.weather.wind,
          weather: response.data.weather.weather,
        }
      };
    }
    
    return { success: false, error: 'Hibás válasz formátum' };
  } catch (error) {
    console.error('❌ Weather error:', error.message);
    console.error('❌ Response data:', error.response?.data);
    return { success: false, error: error.message };
  }
};

// ========== FAVORITES ==========
export const getFavorites = async () => {
  try {
    console.log('⭐ Fetching favorites...');
    
    const response = await api.get('/api/favorites');
    
    console.log('✅ Favorites response status:', response.status);
    console.log('📦 Favorites data:', response.data);

    if (response.data && response.data.success) {
      return { success: true, data: response.data.favorites };
    }
    
    return { success: false, error: 'Nincs bejelentkezve' };
  } catch (error) {
    console.error('❌ Get favorites error:', error.message);
    return { success: false, error: error.message };
  }
};

export const addFavorite = async (cityId) => {
  try {
    console.log('➕ Adding favorite, city ID:', cityId);
    
    const formData = new FormData();
    formData.append('city_id', cityId);

    const response = await api.post('/api/favorite/add', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    console.log('✅ Add favorite response:', response.status);
    console.log('📦 Add favorite data:', response.data);

    if (response.data && response.data.success) {
      return { success: true };
    }

    return { success: false, error: 'Hozzáadás sikertelen' };
  } catch (error) {
    console.error('❌ Add favorite error:', error.message);
    return { success: false, error: error.message };
  }
};

export const removeFavorite = async (cityId) => {
  try {
    console.log('➖ Removing favorite, city ID:', cityId);
    
    const formData = new FormData();
    formData.append('city_id', cityId);

    const response = await api.post('/api/favorite/remove', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    console.log('✅ Remove favorite response:', response.status);

    if (response.data && response.data.success) {
      return { success: true };
    }

    return { success: false, error: 'Törlés sikertelen' };
  } catch (error) {
    console.error('❌ Remove favorite error:', error.message);
    return { success: false, error: error.message };
  }
};

export const isFavorite = async (cityId) => {
  try {
    const result = await getFavorites();
    if (result.success && result.data) {
      return result.data.some(fav => fav.id === cityId);
    }
    return false;
  } catch (error) {
    return false;
  }
};

export const register = async (email, password) => {
  try {
    console.log('📝 Register attempt:', email);
    
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);

    const response = await api.post('/register', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });

    console.log('✅ Register response:', response.status);

    if (response.status === 200 || response.status === 302) {
      return { success: true, data: 'Regisztráció sikeres! Ellenőrizd az email-t!' };
    }

    return { success: false, error: 'Regisztráció sikertelen' };
  } catch (error) {
    console.error('❌ Register error:', error.message);
    
    if (error.response?.status === 302) {
      return { success: true, data: 'Regisztráció sikeres!' };
    }
    return { success: false, error: error.message };
  }
};