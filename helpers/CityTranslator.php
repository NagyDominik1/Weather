<?php
/**
 * VÁROSNÉV FORDÍTÁSI HELPER
 *
 * Az OpenWeather API különböző nyelveken adja vissza a városneveket.
 * Ez a helper segít lefordítani őket a beállított nyelvre.
 */

function translateCityName($cityName, $targetLang = null) {
    // Ha nincs megadva célnyelv, session/cookie-ból vesszük
    if ($targetLang === null) {
        $targetLang = $_COOKIE['language'] ?? $_SESSION['language'] ?? 'hu';
    }

    // Város fordítási táblázat
    $cityTranslations = [
        // Magyar városok
        'Budapest' => [
            'hu' => 'Budapest',
            'en' => 'Budapest',
            'sr' => 'Будимпешта'
        ],
        'Szeged' => [
            'hu' => 'Szeged',
            'en' => 'Szeged',
            'sr' => 'Сегедин'
        ],
        'Debrecen' => [
            'hu' => 'Debrecen',
            'en' => 'Debrecen',
            'sr' => 'Дебрецин'
        ],

        // Lengyel városok
        'Krakow' => [
            'hu' => 'Krakkó',
            'en' => 'Krakow',
            'sr' => 'Краков'
        ],

        // Szerb városok
        'Belgrade' => [
            'hu' => 'Belgrád',
            'en' => 'Belgrade',
            'sr' => 'Београд'
        ],
        'Subotica' => [
            'hu' => 'Szabadka',
            'en' => 'Subotica',
            'sr' => 'Суботица'
        ],
        'Vrbas' => [
            'hu' => 'Verbász',
            'en' => 'Vrbas',
            'sr' => 'Врбас'
        ],
        'Novi Sad' => [
            'hu' => 'Újvidék',
            'en' => 'Novi Sad',
            'sr' => 'Нови Сад'
        ],
        'Niš' => [
            'hu' => 'Nis',
            'en' => 'Niš',
            'sr' => 'Ниш'
        ],

        // Európai nagyvárosok
        'Paris' => [
            'hu' => 'Párizs',
            'en' => 'Paris',
            'sr' => 'Париз'
        ],
        'London' => [
            'hu' => 'London',
            'en' => 'London',
            'sr' => 'Лондон'
        ],
        'Berlin' => [
            'hu' => 'Berlin',
            'en' => 'Berlin',
            'sr' => 'Берлин'
        ],
        'Vienna' => [
            'hu' => 'Bécs',
            'en' => 'Vienna',
            'sr' => 'Беч'
        ],
        'Prague' => [
            'hu' => 'Prága',
            'en' => 'Prague',
            'sr' => 'Праг'
        ],
        'Warsaw' => [
            'hu' => 'Varsó',
            'en' => 'Warsaw',
            'sr' => 'Варшава'
        ],
        'Rome' => [
            'hu' => 'Róma',
            'en' => 'Rome',
            'sr' => 'Рим'
        ],
        'Athens' => [
            'hu' => 'Athén',
            'en' => 'Athens',
            'sr' => 'Атина'
        ],
        'Moscow' => [
            'hu' => 'Moszkva',
            'en' => 'Moscow',
            'sr' => 'Москва'
        ],
        'Copenhagen' => [
            'hu' => 'Koppenhága',
            'en' => 'Copenhagen',
            'sr' => 'Копенхаген'
        ],
        'Stockholm' => [
            'hu' => 'Stockholm',
            'en' => 'Stockholm',
            'sr' => 'Стокхолм'
        ],
        'Bucharest' => [
            'hu' => 'Bukarest',
            'en' => 'Bucharest',
            'sr' => 'Букурешт'
        ],
        'Zagreb' => [
            'hu' => 'Zágráb',
            'en' => 'Zagreb',
            'sr' => 'Загреб'
        ],
        'Ljubljana' => [
            'hu' => 'Ljubljana',
            'en' => 'Ljubljana',
            'sr' => 'Љубљана'
        ],
        'Bratislava' => [
            'hu' => 'Pozsony',
            'en' => 'Bratislava',
            'sr' => 'Братислава'
        ],
    ];

    // Normalizáljuk a városnevet (eltávolítjuk az akcentusokat összehasonlításhoz)
    $normalizedCity = $cityName;

    // Keresés a táblázatban - megpróbáljuk megtalálni bármelyik nyelvű megfelelőt
    foreach ($cityTranslations as $baseCity => $translations) {
        // Ha a keresett város megegyezik valamelyik fordítással
        if (in_array($cityName, $translations) || $cityName === $baseCity) {
            // Visszaadjuk a célnyelvi fordítást
            return $translations[$targetLang] ?? $cityName;
        }
    }

    // Ha nincs fordítás, visszaadjuk az eredeti nevet
    return $cityName;
}

// Helper funkció: város fordítása + HTML escape
function getCityDisplayName($cityName, $targetLang = null) {
    return htmlspecialchars(translateCityName($cityName, $targetLang));
}