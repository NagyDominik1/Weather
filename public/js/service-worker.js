// Service Worker - WeatherBase PWA
const CACHE_NAME = 'weatherbase-v2';
const urlsToCache = [
    '/iws-2025-hu/Projekt-iws/public/',
    '/iws-2025-hu/Projekt-iws/public/login',
    '/iws-2025-hu/Projekt-iws/public/register',
    // CSS (Tailwind CDN - mindig friss legyen)
    // JS fájlok
    '/iws-2025-hu/Projekt-iws/public/js/validation.js',
    // Fontok, ikonok
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

// Telepítés
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Service Worker: Cache megnyitva');
                return cache.addAll(urlsToCache);
            })
    );
    self.skipWaiting();
});

// Aktiválás
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        console.log('Service Worker: Régi cache törlése', cache);
                        return caches.delete(cache);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});

// Fetch - NetworkFirst stratégia (mindig friss adatok)
self.addEventListener('fetch', (event) => {
    // Csak GET kéréseket cache-elünk
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Ha sikeres a network kérés, cache-eljük
                if (response && response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Ha nincs net, cache-ből adjuk vissza
                return caches.match(event.request).then((response) => {
                    if (response) {
                        return response;
                    }
                    // Offline fallback oldal (opcionális)
                    if (event.request.mode === 'navigate') {
                        return caches.match('/iws-2025-hu/Projekt-iws/public/');
                    }
                });
            })
    );
});

// Background Sync (opcionális - későbbi fejlesztéshez)
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-weather-data') {
        event.waitUntil(syncWeatherData());
    }
});

async function syncWeatherData() {
    // TODO: Szinkronizálni az offline során mentett kedvenc városokat
    console.log('Background sync: Weather data');
}