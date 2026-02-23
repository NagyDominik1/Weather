<?php

class WeatherService {

    private PDO $db;
    private string $apiKey = "4a3ff1b88c000f9b446042147d9f14f5";

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // ===============================
    // IDŐJÁRÁS LEKÉRÉS + MENTÉS
    // ===============================
    public function fetchAndSaveWeatherByCityName(string $cityName, ?string $lang = null, ?int $userId = null): array
    {
        if ($lang === null) {
            $lang = $_SESSION['lang'] ?? 'hu';
        }

        $url = "https://api.openweathermap.org/data/2.5/weather?q="
            . urlencode($cityName)
            . "&appid={$this->apiKey}&units=metric&lang={$lang}";

        $response = @file_get_contents($url);
        if (!$response) {
            throw new Exception("Hiba: Város nem található vagy API hiba.");
        }

        $data = json_decode($response, true);

        // ---- CITY ----
        $stmt = $this->db->prepare("SELECT id FROM cities WHERE city_name = ? LIMIT 1");
        $stmt->execute([$cityName]);
        $city = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$city) {
            $stmt = $this->db->prepare("
                INSERT INTO cities (city_name, country, lat, lon)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['name'],
                $data['sys']['country'],
                $data['coord']['lat'],
                $data['coord']['lon']
            ]);
            $cityId = (int)$this->db->lastInsertId();
        } else {
            $cityId = (int)$city['id'];
        }

        // ---- WEATHER SAVE ----
        $stmt = $this->db->prepare("
            INSERT INTO weather_data
            (user_id, city_id, temperature, feels_like, humidity, wind_speed, description, icon, dt)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $userId,
            $cityId,
            $data['main']['temp'],
            $data['main']['feels_like'],
            $data['main']['humidity'],
            $data['wind']['speed'],
            $data['weather'][0]['description'],
            $data['weather'][0]['icon']
        ]);

        return [
            'data' => $data,
            'city_id' => $cityId
        ];
    }

    // ===============================
    // ÖLTÖZKÖDÉSI AJÁNLÁS – DINAMIKUS
    // ===============================
    public function getOutfitRecommendation(float $temp, string $lang): string
    {
        $column = 'recommendation';

        if ($lang === 'en') {
            $column = 'recommendation_en';
        } elseif ($lang === 'sr' || $lang === 'rs') {
            $column = 'recommendation_sr';
        }

        $stmt = $this->db->prepare("
            SELECT {$column} AS text
            FROM outfit_recommendations
            WHERE :temp BETWEEN temp_min AND temp_max
            LIMIT 1
        ");
        $stmt->execute(['temp' => $temp]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row['text'])) {
            return $row['text'];
        }

        // fallback HU
        $stmt = $this->db->prepare("
            SELECT recommendation
            FROM outfit_recommendations
            WHERE :temp BETWEEN temp_min AND temp_max
            LIMIT 1
        ");
        $stmt->execute(['temp' => $temp]);

        return $stmt->fetchColumn() ?: 'Nincs ajánlás';
    }

    // ===============================
    // ELŐREJELZÉS
    // ===============================
    public function getForecast(string $cityName, string $lang): array
    {
        $url = "https://api.openweathermap.org/data/2.5/forecast?q="
            . urlencode($cityName)
            . "&appid={$this->apiKey}&units=metric&lang={$lang}";

        $json = @file_get_contents($url);
        if (!$json) return [];

        $data = json_decode($json, true);
        $daily = [];

        foreach ($data['list'] ?? [] as $item) {
            if (str_contains($item['dt_txt'], '12:00:00')) {
                $daily[] = [
                    'date' => date('m. d.', $item['dt']),
                    'temp' => round($item['main']['temp']),
                    'icon' => $item['weather'][0]['icon'],
                    'desc' => $item['weather'][0]['description']
                ];
            }
        }

        return $daily;
    }

}