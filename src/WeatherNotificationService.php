<?php
/**
 * WEATHER NOTIFICATION SERVICE
 *
 * Ez a class kezeli az értesítéseket:
 * - Email értesítések időjárás változásról
 * - Push értesítések (böngésző)
 * - Vészjelzések
 * - Napi összefoglaló
 */

class WeatherNotificationService {
    private $db;
    private $emailService;

    public function __construct($db) {
        $this->db = $db;
        $this->emailService = new EmailService();
    }

    /**
     * Ellenőrzi, hogy kell-e értesítést küldeni
     */
    public function checkAndSendNotifications($userId, $cityName, $weatherData) {
        // Felhasználó beállításainak lekérése
        $stmt = $this->db->prepare("
            SELECT email, 
                   notify_email, 
                   notify_push, 
                   notify_alerts, 
                   notify_daily 
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        // 1. VÉSZJELZÉSEK ellenőrzése (vihar, extrém hőmérséklet)
        if ($user['notify_alerts']) {
            $this->checkEmergencyAlerts($user['email'], $cityName, $weatherData);
        }

        // 2. EMAIL értesítés jelentős változásról
        if ($user['notify_email']) {
            $this->checkWeatherChange($user['email'], $cityName, $weatherData);
        }

        // 3. PUSH értesítés (ezt később lehet implementálni js-sel)
        if ($user['notify_push']) {
            // Browser notification API - ezt a frontend-en kell megcsinálni
        }

        return true;
    }

    /**
     * Ellenőrzi az extrém időjárást
     */
    private function checkEmergencyAlerts($email, $cityName, $weatherData) {
        $alerts = [];

        // Extrém hőmérséklet
        if ($weatherData['main']['temp'] > 35) {
            $alerts[] = "⚠️ Extrém hőség: " . round($weatherData['main']['temp']) . "°C";
        }
        if ($weatherData['main']['temp'] < -10) {
            $alerts[] = "❄️ Extrém hideg: " . round($weatherData['main']['temp']) . "°C";
        }

        // Vihar
        $weatherId = $weatherData['weather'][0]['id'];
        if ($weatherId >= 200 && $weatherId < 300) {
            $alerts[] = "⛈️ VIHAR FIGYELMEZTETÉS!";
        }

        // Erős szél
        if (isset($weatherData['wind']['speed']) && $weatherData['wind']['speed'] > 15) {
            $alerts[] = "💨 Erős szél: " . $weatherData['wind']['speed'] . " m/s";
        }

        // Ha van vészjelzés, küldjük el
        if (!empty($alerts)) {
            $this->sendEmergencyEmail($email, $cityName, $alerts);
        }
    }

    /**
     * Vészjelzés email küldése
     */
    private function sendEmergencyEmail($email, $cityName, $alerts) {
        $subject = "⚠️ IDŐJÁRÁSI VÉSZJELZÉS - " . $cityName;

        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { background: white; border-radius: 10px; padding: 30px; max-width: 600px; margin: 0 auto; }
                .alert { background: #fee; border-left: 4px solid #e00; padding: 15px; margin: 10px 0; border-radius: 5px; }
                .footer { color: #888; font-size: 12px; margin-top: 30px; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1 style='color: #e00;'>⚠️ Időjárási vészjelzés!</h1>
                <p><strong>Helyszín:</strong> {$cityName}</p>
                <p>Az alábbi vészjelzéseket észleltük:</p>
                ";

        foreach ($alerts as $alert) {
            $message .= "<div class='alert'>{$alert}</div>";
        }

        $message .= "
                <p style='margin-top: 20px;'>Kérjük, maradjon biztonságban és kövesse a helyi hatóságok utasításait!</p>
                <div class='footer'>
                    <p>Ez egy automatikus értesítés a Weather App-tól.</p>
                    <p>Beállításaid módosításához látogass el a Beállítások oldalra.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $this->emailService->sendWeatherAlert($email, $subject, $message);
    }

    /**
     * Időjárás változás értesítés
     */
    private function checkWeatherChange($email, $cityName, $weatherData) {
        // Előző adat lekérése
        $stmt = $this->db->prepare("
            SELECT temperature, description 
            FROM weather_data 
            WHERE city_id = (SELECT id FROM cities WHERE city_name = ?) 
            ORDER BY dt DESC 
            LIMIT 1, 1
        ");
        $stmt->execute([$cityName]);
        $previousData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$previousData) {
            return; // Nincs korábbi adat
        }

        // Jelentős hőmérséklet változás (5+ fok)
        $tempDiff = abs($weatherData['main']['temp'] - $previousData['temperature']);

        if ($tempDiff >= 5) {
            $this->sendWeatherChangeEmail($email, $cityName, $weatherData, $previousData, $tempDiff);
        }
    }

    /**
     * Időjárás változás email
     */
    private function sendWeatherChangeEmail($email, $cityName, $currentData, $previousData, $tempDiff) {
        $subject = "🌤️ Időjárás változás - " . $cityName;

        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { background: white; border-radius: 10px; padding: 30px; max-width: 600px; margin: 0 auto; }
                .stat { background: #e3f2fd; padding: 15px; margin: 10px 0; border-radius: 5px; }
                .footer { color: #888; font-size: 12px; margin-top: 30px; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1 style='color: #2196f3;'>🌤️ Időjárás változás!</h1>
                <p><strong>Helyszín:</strong> {$cityName}</p>
                
                <div class='stat'>
                    <h3>Jelenlegi hőmérséklet:</h3>
                    <p style='font-size: 24px; font-weight: bold;'>" . round($currentData['main']['temp']) . "°C</p>
                    <p>" . htmlspecialchars($currentData['weather'][0]['description']) . "</p>
                </div>
                
                <div class='stat'>
                    <h3>Változás:</h3>
                    <p style='font-size: 20px; color: #f44336;'>" . round($tempDiff) . "°C különbség!</p>
                </div>
                
                <div class='footer'>
                    <p>Ez egy automatikus értesítés a Weather App-tól.</p>
                    <p>Beállításaid módosításához látogass el a Beállítások oldalra.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $this->emailService->sendWeatherAlert($email, $subject, $message);
    }

    /**
     * Napi összefoglaló küldése (ezt cron job-ból kell hívni)
     */
    public function sendDailySummary() {
        // Minden felhasználó, akinek be van kapcsolva
        $stmt = $this->db->prepare("
            SELECT u.id, u.email 
            FROM users u 
            WHERE u.notify_daily = 1 AND u.is_active = 1
        ");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            $this->sendDailySummaryToUser($user['id'], $user['email']);
        }
    }

    /**
     * Napi összefoglaló egyetlen usernek
     */
    private function sendDailySummaryToUser($userId, $email) {
        // Kedvenc városok időjárása
        $stmt = $this->db->prepare("
            SELECT c.city_name, wd.temperature, wd.description 
            FROM favorite_cities fc
            JOIN cities c ON fc.city_id = c.id
            LEFT JOIN weather_data wd ON c.id = wd.city_id
            WHERE fc.user_id = ?
            ORDER BY wd.dt DESC
        ");
        $stmt->execute([$userId]);
        $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($favorites)) {
            return; // Nincs kedvenc város
        }

        $subject = "🌅 Napi időjárás összefoglaló";

        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { background: white; border-radius: 10px; padding: 30px; max-width: 600px; margin: 0 auto; }
                .city { background: #f0f4f8; padding: 15px; margin: 10px 0; border-radius: 5px; }
                .footer { color: #888; font-size: 12px; margin-top: 30px; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1 style='color: #1976d2;'>🌅 Jó reggelt!</h1>
                <p>Itt a napi időjárás összefoglalód a kedvenc városaidból:</p>
        ";

        foreach ($favorites as $fav) {
            $message .= "
                <div class='city'>
                    <h3>" . htmlspecialchars($fav['city_name']) . "</h3>
                    <p style='font-size: 20px; font-weight: bold;'>" . round($fav['temperature']) . "°C</p>
                    <p>" . htmlspecialchars($fav['description']) . "</p>
                </div>
            ";
        }

        $message .= "
                <div class='footer'>
                    <p>Szép napot kívánunk! 🌤️</p>
                    <p>Weather App - Az időjárás mindig kéznél</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $this->emailService->sendWeatherAlert($email, $subject, $message);
    }
}