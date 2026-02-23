<?php
// FORDÍTÁSI HELPER FUNKCIÓ

// Globális változó a fordításokhoz
if (!isset($GLOBALS['translations'])) {
    // Cookie-ból vagy session-ből kiolvasás
    $lang = $_COOKIE['language'] ?? $_SESSION['language'] ?? 'hu';

    // Betöltjük a megfelelő nyelvi fájlt
    $translationFile = __DIR__ . '/translations/' . $lang . '.php';

    if (file_exists($translationFile)) {
        $GLOBALS['translations'] = require $translationFile;
    } else {
        // Ha nem létezik, magyar alapértelmezett
        $GLOBALS['translations'] = require __DIR__ . '/translations/hu.php';
    }

    // Nyelv változó is globális legyen
    $GLOBALS['current_lang'] = $lang;
}

// Helper funkció: fordítás lekérése
if (!function_exists('t')) {
    function t($key) {
        return $GLOBALS['translations'][$key] ?? $key;
    }
}

// Helper funkció: fordítás kiírása
if (!function_exists('e')) {
    function e($key) {
        echo htmlspecialchars(t($key));
    }
}

// Változók elérhetővé tétele a template-ekben
$translations = $GLOBALS['translations'];
$lang = $GLOBALS['current_lang'];