<?php

class WeatherService
{
    private PDO $db;
    private string $apiKey = '4a3ff1b88c000f9b446042147d9f14f5';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function fetchAndSaveWeather(int $cityId): array
    {
        // 1️⃣ Város lekérése
        $stmt = $this->db->prepare("SELECT lat, lon FROM cities WHERE id = ?");
        $stmt->execute([$cityId]);
        $city = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$city) {
            throw new Exception('City not found');
        }

        // 2️⃣ API URL
        $url = "https://api.openweathermap.org/data/2.5/weather"
            . "?lat={$city['lat']}&lon={$city['lon']}"
            . "&appid={$this->apiKey}&units=metric";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
        ]);

        $responseJson = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($responseJson, true);

        if (!isset($response['main'])) {
            throw new Exception('Invalid API response');
        }

        // 3️⃣ Mentés adatbázisba (MEZŐK EGYEZNEK!)
        $stmt = $this->db->prepare("
            INSERT INTO weather_data
            (city_id, temperature, feels_like, humidity, wind_speed, description, icon, pressure, visibility, clouds, sunrise, sunset, dt)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FROM_UNIXTIME(?), FROM_UNIXTIME(?), NOW())
        ");

        $stmt->execute([
            $cityId,
            $response['main']['temp'] ?? null,
            $response['main']['feels_like'] ?? null,
            $response['main']['humidity'] ?? null,
            $response['wind']['speed'] ?? null,
            $response['weather'][0]['description'] ?? null,
            $response['weather'][0]['icon'] ?? null,
            $response['main']['pressure'] ?? null,
            $response['visibility'] ?? null,
            $response['clouds']['all'] ?? null,
            $response['sys']['sunrise'] ?? null,
            $response['sys']['sunset'] ?? null
        ]);

        return $response;
    }
}
