<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/WeatherService.php';
require_once __DIR__ . '/../src/EmailService.php'; // Ezt ne felejtsd ki!

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->setBasePath('/iws-2025-hu/Projekt-iws/public');
$app->addBodyParsingMiddleware();

/**
 * FŐOLDAL - Kedvencek listázása
 */
$app->get('/', function ($request, $response) {
    $db = Database::getConnection();
    $userId = 1;

    $cities = $db->query("SELECT id, city_name FROM cities ORDER BY city_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    $favStmt = $db->prepare("
        SELECT c.city_name, c.id 
        FROM favorite_cities f 
        JOIN cities c ON f.city_id = c.id 
        WHERE f.user_id = ?
    ");
    $favStmt->execute([$userId]);
    $favorites = $favStmt->fetchAll(PDO::FETCH_ASSOC);

    ob_start();
    require __DIR__ . '/../templates/home.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

/**
 * IDŐJÁRÁS MEGJELENÍTÉSE
 */
$app->get('/weather', function ($request, $response) {
    $cityName = $request->getQueryParams()['city_name'] ?? null;

    if (!$cityName) {
        return $response->withHeader('Location', './')->withStatus(302);
    }

    $db = Database::getConnection();
    $service = new WeatherService($db);
    $userId = 1;

    try {
        // A service most már egy tömböt ad vissza!
        $result = $service->fetchAndSaveWeatherByCityName($cityName);
        $data = $result['api_data'];
        $cityId = $result['city_id'];

        // Öltözködési javaslat lekérése a DB-ből
        $temp = $data['main']['temp'] ?? 0;
        $outfitStmt = $db->prepare("
            SELECT recommendation 
            FROM outfit_recommendations 
            WHERE ? >= temp_min AND ? <= temp_max 
            LIMIT 1
        ");
        $outfitStmt->execute([$temp, $temp]);
        $recommendation = $outfitStmt->fetchColumn() ?: "Nincs konkrét javaslatunk.";

        // Kedvenc állapot ellenőrzése a friss ID alapján
        $isFavorite = false;
        if ($cityId) {
            $checkFav = $db->prepare("SELECT 1 FROM favorite_cities WHERE user_id = ? AND city_id = ?");
            $checkFav->execute([$userId, $cityId]);
            $isFavorite = (bool)$checkFav->fetch();
        }

        $viewData = [
            'city' => $data['name'], // Az API-tól kapott pontos név
            'city_id' => $cityId,
            'is_favorite' => $isFavorite,
            'data' => $data,
            'recommendation' => $recommendation
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

/**
 * KEDVENC HOZZÁADÁSA
 */
$app->post('/favorite/add', function ($request, $response) {
    $cityId = $request->getParsedBody()['city_id'] ?? null;
    if ($cityId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT IGNORE INTO favorite_cities (user_id, city_id, created_at) VALUES (1, ?, NOW())");
        $stmt->execute([$cityId]);
    }
    return $response->withHeader('Location', $request->getHeaderLine('Referer'))->withStatus(302);
});

/**
 * KEDVENC ELTÁVOLÍTÁSA
 */
$app->post('/favorite/remove', function ($request, $response) {
    $cityId = $request->getParsedBody()['city_id'] ?? null;
    if ($cityId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM favorite_cities WHERE user_id = 1 AND city_id = ?");
        $stmt->execute([$cityId]);
    }
    return $response->withHeader('Location', $request->getHeaderLine('Referer'))->withStatus(302);
});

/**
 * DEDIKÁLT KEDVENCEK OLDAL
 */
$app->get('/favorites', function ($request, $response) {
    $db = Database::getConnection();
    $userId = 1;

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

    // Bcrypt hash-elés
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $activationCode = bin2hex(random_bytes(16));

    $db = Database::getConnection();

    // Email egyediség ellenőrzése
    $check = $db->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        die("Ez az email már regisztrálva van!");
    }

    $stmt = $db->prepare("INSERT INTO users (email, password, activation_code, is_active) VALUES (?, ?, ?, 0)");
    if ($stmt->execute([$email, $hashedPassword, $activationCode])) {
        EmailService::sendActivationEmail($email, $activationCode);
        $response->getBody()->write("Regisztráció sikeres! Ellenőrizd a Mailtrap fiókodat az aktiváláshoz.");
    }
    return $response;
});

$app->get('/activate', function ($request, $response) {
    $code = $request->getQueryParams()['code'] ?? null;

    if (!$code) {
        return $response->withHeader('Location', './login?error=missing_code')->withStatus(302);
    }

    $db = Database::getConnection();
    // Megkeressük a kódot, és ha megvan, aktiváljuk a júzert
    $stmt = $db->prepare("UPDATE users SET is_active = 1, activation_code = NULL WHERE activation_code = ?");
    $stmt->execute([$code]);

    if ($stmt->rowCount() > 0) {
        // Sikeres aktiválás után mehet a loginra
        return $response->withHeader('Location', './login?activated=1')->withStatus(302);
    } else {
        return $response->withHeader('Location', './login?error=invalid_code')->withStatus(302);
    }
});

$app->get('/archive', function ($request, $response) {
    // Csak bejelentkezett felhasználó láthatja (Security)
    if (!isset($_SESSION['user_id'])) {
        return $response->withHeader('Location', './login')->withStatus(302);
    }

    $db = Database::getConnection();
    $cityId = $request->getQueryParams()['city_id'] ?? null;
    $dateFrom = $request->getQueryParams()['date_from'] ?? null;

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

    // Szükségünk van a városok listájára is a lenyíló menühöz
    $cities = $db->query("SELECT id, city_name FROM cities ORDER BY city_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    ob_start();
    require __DIR__ . '/../templates/archive.php';
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/login', function ($request, $response) {
    // Ha már be van lépve, ne engedjük újra a loginra, dobjuk a főoldalra
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
    // Keressük a felhasználót (legyen aktív!)
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // ADATOK ELMENTÉSE - Ettől fog változni a Sidebar
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Biztosítsuk, hogy a session azonnal mentődjön
        session_write_close();

        // Átirányítás a főoldalra
        return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/')->withStatus(302);
    }

    // Hiba esetén vissza a loginra hibaüzenettel
    return $response->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/login?error=1')->withStatus(302);
});
$app->get('/register', function ($request, $response) {
    ob_start();
    require __DIR__ . '/../templates/register.php'; // Győződj meg róla, hogy létezik ez a fájl!
    $html = ob_get_clean();
    $response->getBody()->write($html);
    return $response;
});

$app->get('/logout', function ($request, $response) {
    // 1. Session ürítése
    $_SESSION = array();

    // 2. Session megsemmisítése
    if (session_id() != "" || isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 2592000, '/');
    }
    session_destroy();

    // 3. Átirányítás a főoldalra (bejelentkezés nélküli állapotba)
    return $response
        ->withHeader('Location', '/iws-2025-hu/Projekt-iws/public/')
        ->withStatus(302);
});

$app->run();