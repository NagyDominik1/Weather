<?php

class WeatherService {
    private $db;
    private $apiKey = "4a3ff1b88c000f9b446042147d9f14f5";

    public function __construct($db) {
        $this->db = $db;
    }

    public function fetchAndSaveWeatherByCityName($cityName) {
        // 1. LEKÉRÉS AZ API-BÓL
        $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($cityName) . "&units=metric&lang=hu&appid=" . $this->apiKey;
        $response = @file_get_contents($url);

        if (!$response) {
            throw new Exception("Hiba: Város nem található vagy API hiba.");
        }

        $data = json_decode($response, true);

        // 2. VÁROS KERESÉSE VAGY LÉTREHOZÁSA
        $stmt = $this->db->prepare("SELECT id FROM cities WHERE city_name = ? LIMIT 1");
        $stmt->execute([$cityName]);
        $city = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$city) {
            // Ha kitörölted a DB-t, itt szúrjuk be újra automatikusan
            $insertCity = $this->db->prepare("INSERT INTO cities (city_name, country, lat, lon) VALUES (?, ?, ?, ?)");
            $insertCity->execute([
                $data['name'],
                $data['sys']['country'],
                $data['coord']['lat'],
                $data['coord']['lon']
            ]);
            $cityId = $this->db->lastInsertId();
        } else {
            $cityId = $city['id'];
            // Ha már benne volt, frissítjük a koordinátákat
            $updateCity = $this->db->prepare("UPDATE cities SET lat = ?, lon = ?, country = ? WHERE id = ?");
            $updateCity->execute([$data['coord']['lat'], $data['coord']['lon'], $data['sys']['country'], $cityId]);
        }

        // 3. ÚJ REKORD A WEATHER_DATA TÁBLÁBA
        $insertWeather = $this->db->prepare("
            INSERT INTO weather_data 
            (city_id, temperature, feels_like, humidity, wind_speed, description, icon, dt) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $insertWeather->execute([
            $cityId,
            $data['main']['temp'],
            $data['main']['feels_like'],
            $data['main']['humidity'],
            $data['wind']['speed'],
            $data['weather'][0]['description'],
            $data['weather'][0]['icon']
        ]);

        // Visszaadjuk az API adatot ÉS a belső ID-t is!
        return [
            'api_data' => $data,
            'city_id' => $cityId
        ];
    }
}