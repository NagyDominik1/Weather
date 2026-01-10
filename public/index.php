<?php

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/WeatherService.php';

$app = AppFactory::create();

/* ha nem virtual host, kell */
$app->setBasePath('/iws-2025-hu/Projekt-iws/public');

/* JSON hibákhoz ajánlott */
$app->addErrorMiddleware(true, true, true);

/* ===== ROUTES ===== */

$app->get('/', function (Request $request, Response $response): Response {
    $response->getBody()->write('Slim OK 🚀');
    return $response;
});

$app->get('/weather/{id}', function (Request $request, Response $response, array $args): Response {
    $db = Database::getConnection();
    $weatherService = new WeatherService($db);

    $data = $weatherService->fetchAndSaveWeather((int)$args['id']);

    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
