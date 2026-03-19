<?php

class WeatherService {
    private PDO $db;
    private string $apiKey = "4a3ff1b88c000f9b446042147d9f14f5";

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function fetchAndSaveWeatherByCityName(string $cityName, ?string $lang = null, $userId = null): array
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

        // ---- CITY KEZELÉS ----
        $stmt = $this->db->prepare("SELECT id FROM cities WHERE city_name = ? LIMIT 1");
        $stmt->execute([$cityName]);
        $city = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$city) {
            $stmt = $this->db->prepare("INSERT INTO cities (city_name, country, lat, lon) VALUES (?, ?, ?, ?)");
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

        // ---- MENTÉS (EXPLICIT NULL KEZELÉSSEL) ✅ ----
        $sql = "INSERT INTO weather_data 
                (user_id, city_id, temperature, feels_like, humidity, wind_speed, description, icon, pressure, visibility, clouds, sunrise, sunset, dt) 
                VALUES (:user_id, :city_id, :temp, :feels, :hum, :wind, :desc, :icon, :pres, :vis, :clouds, :sunrise, :sunset, NOW())";

        $stmt = $this->db->prepare($sql);

        // Itt történik a varázslat: ha a userId 0 vagy null, SQL NULL-t küldünk
        if (is_numeric($userId) && (int)$userId > 0) {
            $stmt->bindValue(':user_id', (int)$userId, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':user_id', null, PDO::PARAM_NULL);
        }

        $stmt->bindValue(':city_id', $cityId, PDO::PARAM_INT);
        $stmt->bindValue(':temp', $data['main']['temp']);
        $stmt->bindValue(':feels', $data['main']['feels_like']);
        $stmt->bindValue(':hum', $data['main']['humidity']);
        $stmt->bindValue(':wind', $data['wind']['speed']);
        $stmt->bindValue(':desc', $data['weather'][0]['description']);
        $stmt->bindValue(':icon', $data['weather'][0]['icon']);
        $stmt->bindValue(':pres', $data['main']['pressure'] ?? null);
        $stmt->bindValue(':vis', $data['visibility'] ?? null);
        $stmt->bindValue(':clouds', $data['clouds']['all'] ?? null);

        // Dátum formátumok (UNIX -> MySQL DATETIME)
        $sunrise = isset($data['sys']['sunrise']) ? date('Y-m-d H:i:s', $data['sys']['sunrise']) : null;
        $sunset = isset($data['sys']['sunset']) ? date('Y-m-d H:i:s', $data['sys']['sunset']) : null;

        $stmt->bindValue(':sunrise', $sunrise);
        $stmt->bindValue(':sunset', $sunset);

        $stmt->execute();

        return [
            'data' => $data,
            'city_id' => $cityId
        ];
    }

    // A többi függvény (getOutfitRecommendation, getForecast) maradhat...
    public function getOutfitRecommendation(float $temp, string $lang): string {
        $column = (in_array($lang, ['en', 'sr', 'rs'])) ? "recommendation_{$lang}" : 'recommendation';
        $stmt = $this->db->prepare("SELECT {$column} AS text FROM outfit_recommendations WHERE :temp BETWEEN temp_min AND temp_max LIMIT 1");
        $stmt->execute(['temp' => $temp]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['text'] ?? 'Nincs ajánlás';
    }

    public function getForecast(string $cityName, string $lang): array {
        $url = "https://api.openweathermap.org/data/2.5/forecast?q=" . urlencode($cityName) . "&appid={$this->apiKey}&units=metric&lang={$lang}";
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