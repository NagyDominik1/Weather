<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/WeatherService.php';
require_once __DIR__ . '/../src/EmailService.php';
require_once __DIR__ . '/../src/WeatherNotificationService.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app = AppFactory::create();

/**
 * AUTOMATIKUS BÁZIS ÚTVONAL FELISMERÉS ✅
 * Ez működik XAMPP-on és az egyetemi szerveren is.
 */
$scriptName = $_SERVER['SCRIPT_NAME']; // pl. /iws-2025-hu/Projekt-iws/public/index.php
$basePath = str_replace('/index.php', '', $scriptName);

// Ha a basePath nem üres (tehát almappában vagyunk, mint a XAMPP-on vagy a stud szerveren)
if (!empty($basePath)) {
    $app->setBasePath($basePath);
}

$app->addBodyParsingMiddleware();

$app->get('/', function ($request, $response) {
    $db = Database::getConnection();
    $userId = $_SESSION['user_id'] ?? null;

    $favorites = [];
    if ($userId) {
        $favStmt = $db->prepare("
            SELECT c.city_name, c.id 
            FROM favorite_cities f 
            JOIN cities c ON f.city_id = c.id 
            WHERE f.user_id = ?
            ORDER BY c.city_name ASC
        ");
        $favStmt->execute([$userId]);
        $favorites = $favStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    ob_start();
    require __DIR__ . '/../templates/home.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/weather', function ($request, $response) {

    $cityName = $request->getQueryParams()['city_name'] ?? null;
    if (!$cityName) {
        return $response->withHeader('Location', './')->withStatus(302);
    }

    $db = Database::getConnection();
    $service = new WeatherService($db);
    $userId = $_SESSION['user_id'] ?? null;
    $currentLang = $_COOKIE['language'] ?? $_SESSION['language'] ?? 'hu';

    // --- VENDÉG LIMIT ELLENŐRZÉSE (MAX 3 VÁROS) ---
    if (!$userId) {
        // Ha még nincs a sessionben a listánk, létrehozzuk
        if (!isset($_SESSION['guest_history'])) {
            $_SESSION['guest_history'] = [];
        }

        // Ha ez egy ÚJ város (nincs még a 3-as listában)
        if (!in_array($cityName, $_SESSION['guest_history'])) {
            // Megnézzük, teli van-e már a lista
            if (count($_SESSION['guest_history']) >= 3) {
                // Ha igen, irány a login, egy hibaüzenettel
                return $response->withHeader('Location', './login?error=limit_reached')->withStatus(302);
            }
            // Ha nincs teli, hozzáadjuk a várost a történethez
            $_SESSION['guest_history'][] = $cityName;
        }
    }
    // --- LIMIT VÉGE ---

    try {
        // IDŐJÁRÁS + MENTÉS
        $result = $service->fetchAndSaveWeatherByCityName($cityName, $currentLang, $userId);

        // ✅ A te kódod folytatása változatlanul...
        $data   = $result['api_data'] ?? $result['data']; // Figyelj a kulcsra, amit a Service ad vissza!
        $cityId = $result['city_id'];

        // ELŐREJELZÉS
        $forecast = $service->getForecast($cityName, $currentLang);

        // ÖLTÖZKÖDÉSI AJÁNLÁS
        $temp = (float)($data['main']['temp'] ?? 0);
        $recommendation = $service->getOutfitRecommendation($temp, $currentLang);

        $isFavorite = false;
        if ($userId && $cityId) {
            $stmt = $db->prepare("SELECT 1 FROM favorite_cities WHERE user_id = ? AND city_id = ?");
            $stmt->execute([$userId, $cityId]);
            $isFavorite = (bool)$stmt->fetch();
        }

        $viewData = [
            'city' => $data['name'] ?? $cityName,
            'city_id' => $cityId,
            'data' => $data,
            'forecast' => $forecast,
            'recommendation' => $recommendation,
            'is_favorite' => $isFavorite
        ];

    } catch (Exception $e) {
        $response->getBody()->write("Hiba: " . $e->getMessage());
        return $response;
    }

    ob_start();
    require __DIR__ . '/../templates/weather.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});
$app->post('/favorite/add', function ($request, $response) {
    $cityId = $request->getParsedBody()['city_id'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;

    if ($cityId && $userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT IGNORE INTO favorite_cities (user_id, city_id, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$userId, $cityId]);
    }
    return $response->withHeader('Location', $request->getHeaderLine('Referer'))->withStatus(302);
});

$app->post('/favorite/remove', function ($request, $response) {
    $params = $request->getParsedBody();
    $cityId = $params['city_id'] ?? null;
    $userId = $_SESSION['user_id'] ?? null;

    if ($cityId && $userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM favorite_cities WHERE user_id = ? AND city_id = ?");
        $stmt->execute([$userId, $cityId]);
    }

    return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/favorites')->withStatus(302);
});

$app->get('/favorites', function ($request, $response) {
    $db = Database::getConnection();
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    $favStmt = $db->prepare("
        SELECT c.city_name, c.id 
        FROM favorite_cities f 
        JOIN cities c ON f.city_id = c.id 
        WHERE f.user_id = ?
        ORDER BY f.created_at DESC
    ");
    $favStmt->execute([$userId]);
    $favorites = $favStmt->fetchAll(PDO::FETCH_ASSOC);

    ob_start();
    require __DIR__ . '/../templates/favorites.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->post('/register', function ($request, $response) {
    $params = $request->getParsedBody();
    $email = filter_var($params['email'], FILTER_SANITIZE_EMAIL);
    $password = $params['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $activationCode = bin2hex(random_bytes(16));

    $db = Database::getConnection();

    // Regisztráció mentése (inaktívként: is_active = 0)
    $stmt = $db->prepare("INSERT INTO users (email, password, activation_code, is_active) VALUES (?, ?, ?, 0)");

    if ($stmt->execute([$email, $hashedPassword, $activationCode])) {
        // MEHET AZ EMAIL KÜLDÉS
        try {
            $sent = EmailService::sendActivationEmail($email, $activationCode);
            if (!$sent) {
                // Ha a függvény false-t ad vissza
                throw new Exception("A Mailer hiba nélkül tért vissza, de nem küldte el.");
            }
            // Ha sikerült, irány a login üzenettel
            return $response->withHeader('Location', 'login?message=check_email')->withStatus(302);
        } catch (Exception $e) {
            // HA HIBA VAN, ÍRJA KI A KÉPERNYŐRE!
            $response->getBody()->write("<h1>Email hiba történt!</h1>");
            $response->getBody()->write("<p>Üzenet: " . $e->getMessage() . "</p>");
            $response->getBody()->write("<p>A felhasználó létrejött, de aktiválni kell az adatbázisban manuálisan.</p>");
            return $response->withStatus(500);
        }
    }
    return $response;
});
// index.php - részlet
$app->get('/activate', function ($request, $response) {
    $code = $request->getQueryParams()['code'] ?? null;
    $db = Database::getConnection();

    // 1. Megnézzük, létezik-e a kód
    $stmt = $db->prepare("UPDATE users SET is_active = 1, activation_code = NULL WHERE activation_code = ?");
    $stmt->execute([$code]);

    if ($stmt->rowCount() > 0) {
        // SIKER! Írassunk ki valamit, hogy ne legyen üres az oldal
        $response->getBody()->write("<h1>Sikeres aktiválás!</h1><p>Most már bejelentkezhetsz.</p><a href='login'>Tovább a bejelentkezéshez</a>");
        return $response;
    } else {
        $response->getBody()->write("<h1>Hiba!</h1><p>A kód érvénytelen vagy már felhasználták.</p>");
        return $response;
    }
});
$app->get('/archive', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    // City translator betöltése
    require_once __DIR__ . '/../helpers/CityTranslator.php';

    $db = Database::getConnection();
    $cityId = $request->getQueryParams()['city_id'] ?? null;
    $dateFrom = $request->getQueryParams()['date_from'] ?? null;

    // Aktuális nyelv
    $lang = $_COOKIE['language'] ?? $_SESSION['language'] ?? 'hu';

    $query = "SELECT wd.*, c.city_name 
              FROM weather_data wd 
              JOIN cities c ON wd.city_id = c.id 
              WHERE 1=1";
    $params = [];

    if ($cityId) {
        $query .= " AND wd.city_id = ?";
        $params[] = $cityId;
    }
    if ($dateFrom) {
        $query .= " AND DATE(wd.dt) = ?";
        $params[] = $dateFrom;
    }

    $query .= " ORDER BY wd.dt DESC";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // VÁROSOK LEKÉRÉSE - nyelv szerint szűrve
    $allCities = $db->query("
        SELECT DISTINCT city_name 
        FROM cities 
        ORDER BY city_name ASC
    ")->fetchAll(PDO::FETCH_COLUMN);

    // Fordítás + deduplikáció
    $translatedCities = [];
    foreach ($allCities as $cityName) {
        $translated = translateCityName($cityName, $lang);
        if (!isset($translatedCities[$translated])) {
            $translatedCities[$translated] = $cityName; // Eredeti név tárolása
        }
    }

    // Rendezés és átalakítás tömbbé
    ksort($translatedCities);
    $cities = [];
    foreach ($translatedCities as $displayName => $originalName) {
        // ID lekérése az eredeti névhez
        $stmt = $db->prepare("SELECT MIN(id) as id FROM cities WHERE city_name = ?");
        $stmt->execute([$originalName]);
        $cityId = $stmt->fetchColumn();

        $cities[] = [
            'id' => $cityId,
            'city_name' => $displayName
        ];
    }

    ob_start();
    require __DIR__ . '/../templates/archive.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/login', function ($request, $response) {
    if (isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './')->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/../templates/login.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->post('/login', function ($request, $response) {
    $params = $request->getParsedBody();
    $email = $params['email'] ?? '';
    $password = $params['password'] ?? '';

    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        session_write_close();
        return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/')->withStatus(302);
    }

    return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/login?error=1')->withStatus(302);
});

$app->get('/register', function ($request, $response) {
    ob_start();
    require __DIR__ . '/../templates/register.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/logout', function ($request, $response) {
    $_SESSION = array();

    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');
    }
    session_destroy();

    return $response
        ->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/')
        ->withStatus(302);
});

$app->get('/forgot-password', function ($request, $response) {
    ob_start();
    require __DIR__ . '/../templates/forgot-password.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->post('/forgot-password', function ($request, $response) {
    $params = $request->getParsedBody();
    $email = $params['email'] ?? '';
    $token = bin2hex(random_bytes(16));

    $db = Database::getConnection();
    $stmt = $db->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
    $stmt->execute([$token, $email]);

    if ($stmt->rowCount() > 0) {
        EmailService::sendPasswordReset($email, $token);
    }

    $response->getBody()->write("Ha létezik a fiók, elküldtük az e-mailt a Mailtrap-be.");
    return $response;
});

$app->get('/reset-password', function ($request, $response) {
    $token = $request->getQueryParams()['token'] ?? '';

    ob_start();
    require __DIR__ . '/../templates/reset-password.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->post('/update-password', function ($request, $response) {
    $params = $request->getParsedBody();
    $token = $params['token'] ?? '';
    $newPassword = $params['password'] ?? '';

    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    $db = Database::getConnection();
    $stmt = $db->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->execute([$hashedPassword, $token]);

    return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/login?reset=success')->withStatus(302);
});

$app->get('/alerts', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', 'login')->withStatus(302);
    }

    $db = Database::getConnection();
    $userId = $_SESSION['user_id'];

    $alerts = [
        'danger' => [],
        'warning' => [],
        'info' => []
    ];

    try {
        $stmt = $db->prepare("
            SELECT a.*, c.city_name 
            FROM alerts a 
            JOIN cities c ON a.city_id = c.id 
            WHERE a.user_id = ? 
            ORDER BY a.created_at DESC
        ");
        $stmt->execute([$userId]);
        $allAlerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($allAlerts as $alert) {
            $type = strtolower($alert['alert_type'] ?? 'info');

            // Ha a típus 'vészhelyzet', 'hőség', 'vihar' stb, akkor próbáljuk besorolni
            if (strpos($type, 'danger') !== false || strpos($type, 'vész') !== false || strpos($type, 'hőség') !== false) {
                $alerts['danger'][] = $alert;
            } elseif (strpos($type, 'warning') !== false || strpos($type, 'figyelmeztetés') !== false || strpos($type, 'eső') !== false) {
                $alerts['warning'][] = $alert;
            } else {
                $alerts['info'][] = $alert;
            }
        }
    } catch (Exception $e) {
        // Hiba esetén maradnak az üres tömbök
    }

    ob_start();
    require __DIR__ . '/../templates/alerts.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/compare', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    $db = Database::getConnection();
    $service = new WeatherService($db);

    $city1 = $request->getQueryParams()['city1'] ?? null;
    $city2 = $request->getQueryParams()['city2'] ?? null;
    $city3 = $request->getQueryParams()['city3'] ?? null;

    $compareData = [];
    $error = null;

    if ($city1 || $city2 || $city3) {
        $cityNames = array_filter([$city1, $city2, $city3]);
        foreach ($cityNames as $cityName) {
            try {
                $result = $service->fetchAndSaveWeatherByCityName(trim($cityName));
                $compareData[] = [
                    'name' => $result['api_data']['name'],
                    'temp' => $result['api_data']['main']['temp'],
                    'feels_like' => $result['api_data']['main']['feels_like'],
                    'humidity' => $result['api_data']['main']['humidity'],
                    'wind_speed' => $result['api_data']['wind']['speed'],
                    'pressure' => $result['api_data']['main']['pressure'],
                    'description' => $result['api_data']['weather'][0]['description'],
                    'icon' => $result['api_data']['weather'][0]['icon']
                ];
            } catch (Exception $e) {
                $error = "Hiba a város lekérésekor: " . htmlspecialchars($cityName);
            }
        }
    }

    ob_start();
    // Itt adjuk át a változókat a sablonnak
    require __DIR__ . '/../templates/compare.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/profile', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    $db = Database::getConnection();
    $userId = $_SESSION['user_id'];

    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $favCount = $db->prepare("SELECT COUNT(*) FROM favorite_cities WHERE user_id = ?");
    $favCount->execute([$userId]);
    $favoriteCount = $favCount->fetchColumn();

    ob_start();
    require __DIR__ . '/../templates/profile.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->post('/profile/update-email', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    $params = $request->getParsedBody();
    $newEmail = filter_var($params['new_email'], FILTER_SANITIZE_EMAIL);
    $password = $params['password'] ?? '';
    $userId = $_SESSION['user_id'];

    $db = Database::getConnection();

    $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $check = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check->execute([$newEmail, $userId]);

        if (!$check->fetch()) {
            $update = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
            $update->execute([$newEmail, $userId]);
            $_SESSION['email'] = $newEmail;
            return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/profile?success=email_updated')->withStatus(302);
        } else {
            return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/profile?error=email_exists')->withStatus(302);
        }
    }

    return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/profile?error=wrong_password')->withStatus(302);
});

$app->post('/profile/change-password', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    $params = $request->getParsedBody();
    $currentPassword = $params['current_password'] ?? '';
    $newPassword = $params['new_password'] ?? '';
    $confirmPassword = $params['confirm_password'] ?? '';
    $userId = $_SESSION['user_id'];

    if ($newPassword !== $confirmPassword) {
        return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/profile?error=password_mismatch')->withStatus(302);
    }

    $db = Database::getConnection();

    $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($currentPassword, $user['password'])) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $update = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$hashedPassword, $userId]);

        return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/profile?success=password_changed')->withStatus(302);
    }

    return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/profile?error=wrong_current_password')->withStatus(302);
});

$app->get('/settings', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    ob_start();
    require __DIR__ . '/../templates/settings.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

/**
 * BEÁLLÍTÁSOK MENTÉSE - JAVÍTOTT ✅
 */
$app->post('/settings/save', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    $params = $request->getParsedBody();
    $userId = $_SESSION['user_id'];
    $db = Database::getConnection();

    // Mértékegységek
    $_SESSION['temp_unit'] = $params['temp_unit'] ?? 'celsius';
    $_SESSION['wind_unit'] = $params['wind_unit'] ?? 'ms';
    $_SESSION['pressure_unit'] = $params['pressure_unit'] ?? 'hpa';

    // Értesítések
    $notifyEmail = isset($params['notify_email']) ? 1 : 0;
    $notifyPush = isset($params['notify_push']) ? 1 : 0;
    $notifyAlerts = isset($params['notify_alerts']) ? 1 : 0;
    $notifyDaily = isset($params['notify_daily']) ? 1 : 0;

    $_SESSION['notify_email'] = $notifyEmail;
    $_SESSION['notify_push'] = $notifyPush;
    $_SESSION['notify_alerts'] = $notifyAlerts;
    $_SESSION['notify_daily'] = $notifyDaily;

    // ADATBÁZISBA MENTÉS!
    try {
        $stmt = $db->prepare("
            UPDATE users 
            SET notify_email = ?, 
                notify_push = ?, 
                notify_alerts = ?, 
                notify_daily = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $notifyEmail,
            $notifyPush,
            $notifyAlerts,
            $notifyDaily,
            $userId
        ]);
    } catch (Exception $e) {
        error_log("Értesítések mentési hiba: " . $e->getMessage());
    }

    // Megjelenés
    $theme = $params['theme'] ?? 'light';
    $_SESSION['theme'] = $theme;
    setcookie('theme', $theme, time() + (30 * 24 * 60 * 60), '/');

    $_SESSION['animations'] = isset($params['animations']) ? 1 : 0;

    // Nyelv és régió
    $language = $params['language'] ?? 'hu';
    $_SESSION['language'] = $language;
    $_SESSION['timezone'] = $params['timezone'] ?? 'Europe/Budapest';

    setcookie('language', $language, time() + (30 * 24 * 60 * 60), '/');

    return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/settings?success=saved')->withStatus(302);
});

/**
 * TESZT ENDPOINT
 */
$app->get('/test-notification', function ($request, $response) {
    if (!isset($_SESSION['user_id'])) {
        $response->getBody()->write("❌ Hiba: Előbb jelentkezz be! <a href='./login'>Login</a>");
        return $response;
    }

    $db = Database::getConnection();
    $userId = $_SESSION['user_id'];
    $notificationService = new WeatherNotificationService($db);

    $fakeWeatherData = [
        'main' => [
            'temp' => 38,
            'feels_like' => 42,
            'humidity' => 85,
            'pressure' => 1013
        ],
        'weather' => [
            0 => [
                'id' => 211,
                'description' => 'viharos időjárás',
                'icon' => '11d'
            ]
        ],
        'wind' => [
            'speed' => 18
        ],
        'name' => 'Budapest'
    ];

    $html = "<h1>🧪 Értesítés teszt</h1>";
    $html .= "<p>User ID: {$userId}</p>";
    $html .= "<p>Teszt város: Budapest</p>";
    $html .= "<p>Szimuláció: 🔥 38°C + ⛈️ Vihar + 💨 18 m/s szél</p>";
    $html .= "<hr>";

    try {
        $result = $notificationService->checkAndSendNotifications($userId, 'Budapest', $fakeWeatherData);

        if ($result) {
            $html .= "<div style='background: #d4edda; color: #155724; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
            $html .= "<h2>✅ Értesítés sikeresen elküldve!</h2>";
            $html .= "<p>📧 Ellenőrizd a Mailtrap fiókodat!</p>";
            $html .= "<p>Link: <a href='https://mailtrap.io/inboxes' target='_blank'>https://mailtrap.io/inboxes</a></p>";
            $html .= "</div>";
        } else {
            $html .= "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 10px;'>";
            $html .= "<h2>⚠️ Nem került küldésre email</h2>";
            $html .= "<p>Lehetséges okok:</p>";
            $html .= "<ul>";
            $html .= "<li>Nincs bekapcsolva az 'Email értesítések' a Settings-ben</li>";
            $html .= "<li>Nincs bekapcsolva a 'Vészjelzések'</li>";
            $html .= "</ul>";
            $html .= "</div>";
        }

        $html .= "<hr>";
        $html .= "<h3>🔍 Debug infó:</h3>";
        $html .= "<pre>";

        $stmt = $db->prepare("SELECT notify_email, notify_alerts FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        $html .= "Email értesítések: " . ($settings['notify_email'] ? '✅ BE' : '❌ KI') . "\n";
        $html .= "Vészjelzések: " . ($settings['notify_alerts'] ? '✅ BE' : '❌ KI') . "\n";
        $html .= "</pre>";

    } catch (Exception $e) {
        $html .= "<div style='background: #f8d7da; color: #721c24; padding: 20px; border-radius: 10px;'>";
        $html .= "<h2>❌ Hiba történt!</h2>";
        $html .= "<p><strong>Hibaüzenet:</strong> " . $e->getMessage() . "</p>";
        $html .= "<pre>" . $e->getTraceAsString() . "</pre>";
        $html .= "</div>";
    }

    $html .= "<hr>";
    $html .= "<p><a href='./settings'>⚙️ Beállítások</a> | <a href='./'>🏠 Főoldal</a></p>";

    $response->getBody()->write($html);
    return $response;
});



$app->run();