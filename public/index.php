<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/WeatherService.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->setBasePath('/iws-2025-hu/Projekt-iws/public');

/**
 * HOME
 */
$app->get('/home', function ($request, $response) {
    $db = Database::getConnection();

    $stmt = $db->query("SELECT id, city_name FROM cities");
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ob_start();
    require __DIR__ . '/../templates/home.php';
    $html = ob_get_clean();

    $response->getBody()->write($html);
    return $response;
});
/**
 * WEATHER (HTML nézet!)
 */
$app->get('/weather', function ($request, $response) {
    $cityId = (int)($request->getQueryParams()['city_id'] ?? 0);

    if ($cityId === 0) {
        $response->getBody()->write('Nincs kiválasztva város');
        return $response;
    }

    $db = Database::getConnection();
    $service = new WeatherService($db);
    $data = $service->fetchAndSaveWeather($cityId);

    ob_start();
    require __DIR__ . '/../templates/weather.php';
    $html = ob_get_clean();

    $response->getBody()->write($html);
    return $response;
});

/**
 * ROOT (teszt)
 */
$app->get('/', function ($request, $response) {
    $response->getBody()->write('Slim OK 🚀');
    return $response;
});

$app->run();
