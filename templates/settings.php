<?php include __DIR__ . '/lang.php'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" id="html-root">
<head>
    <meta charset="UTF-8">

    <title><?= t('settings_title') ?> - Weather App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <?php include __DIR__ . '/dark-mode-styles.php'; ?>

    <style>
        /* Globális védelem a zoom ellen */
        .settings-container {
            max-width: 100vw;
            overflow-x: hidden;
        }

        .dev-badge {
            font-size: 9px; /* Kisebb betű */
            padding: 1px 6px;
            border-radius: 4px;
            background: #f1f5f9;
            color: #64748b;
            font-weight: 800;
            text-transform: uppercase;
            border: 1px solid #e2e8f0;
            white-space: nowrap; /* Ne törjön ketté a szöveg */
            display: inline-block;
        }

        /* Letiltott állapot - fixált kurzorral */
        .toggle:disabled {
            cursor: not-allowed !important;
            opacity: 0.4;
        }

        .under-development {
            opacity: 0.7;
            pointer-events: none; /* Mobilon így biztosan nem lehet rákattintani */
        }

        /* Megakadályozzuk, hogy a hosszú szövegek kitolják a kártyát */
        .notif-text {
            min-width: 0;
            flex: 1;
        }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen text-slate-800">

<?php include __DIR__ . '/sidebar_smart.php'; ?>

<main class="flex-1 p-8">
    <header class="mb-10">
        <h1 class="text-4xl font-black text-slate-900 mb-2 flex items-center gap-3">
            <i class="fa-solid fa-gear text-slate-600"></i> <?= t('settings_title') ?>
        </h1>
        <p class="text-slate-500 italic"><?= t('settings_subtitle') ?></p>
    </header>

    <?php if (isset($_GET['success'])): ?>
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-2xl animate-pulse" role="alert">
            <p class="font-bold"><i class="fa-solid fa-check-circle mr-2"></i> <?= t('settings_saved') ?></p>
            <p><?= t('settings_saved_text') ?></p>
        </div>
    <?php endif; ?>

    <form action="/iws-2025-hu/Projekt-iws/public/settings/save" method="post" id="settingsForm">
        <div class="max-w-4xl">

            <!-- MÉRTÉKEGYSÉGEK -->
            <div class="bg-white rounded-3xl p-8 mb-6 border border-slate-100 shadow-lg">
                <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-temperature-three-quarters text-red-500"></i> <?= t('settings_units') ?>
                </h3>
                <div class="space-y-6">
                    <!-- Hőmérséklet -->
                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-3"><?= t('settings_temp_unit') ?></label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="temp_unit" value="celsius"
                                        <?= (!isset($_SESSION['temp_unit']) || $_SESSION['temp_unit'] === 'celsius') ? 'checked' : '' ?>
                                       class="peer sr-only">
                                <div class="p-5 rounded-2xl border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition hover:border-blue-400">
                                    <p class="font-black text-slate-900 text-lg">Celsius (°C)</p>
                                    <p class="text-sm text-slate-500 mt-1">Standard Európában</p>
                                </div>
                                <i class="fa-solid fa-circle-check absolute top-3 right-3 text-2xl text-blue-600 hidden peer-checked:block"></i>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="temp_unit" value="fahrenheit"
                                        <?= (isset($_SESSION['temp_unit']) && $_SESSION['temp_unit'] === 'fahrenheit') ? 'checked' : '' ?>
                                       class="peer sr-only">
                                <div class="p-5 rounded-2xl border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition hover:border-blue-400">
                                    <p class="font-black text-slate-900 text-lg">Fahrenheit (°F)</p>
                                    <p class="text-sm text-slate-500 mt-1">USA, Karib-szigetek</p>
                                </div>
                                <i class="fa-solid fa-circle-check absolute top-3 right-3 text-2xl text-blue-600 hidden peer-checked:block"></i>
                            </label>
                        </div>
                    </div>

                    <!-- Szélsebesség -->
                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-3"><?= t('settings_wind_unit') ?></label>
                        <select name="wind_unit" class="w-full p-4 rounded-2xl border-2 border-slate-200 font-medium hover:border-blue-400 transition focus:outline-none focus:border-blue-600">
                            <option value="ms" <?= (!isset($_SESSION['wind_unit']) || $_SESSION['wind_unit'] === 'ms') ? 'selected' : '' ?>>m/s (<?= t('settings_wind_ms') ?>)</option>
                            <option value="kmh" <?= (isset($_SESSION['wind_unit']) && $_SESSION['wind_unit'] === 'kmh') ? 'selected' : '' ?>>km/h (<?= t('settings_wind_kmh') ?>)</option>
                            <option value="mph" <?= (isset($_SESSION['wind_unit']) && $_SESSION['wind_unit'] === 'mph') ? 'selected' : '' ?>>mph (<?= t('settings_wind_mph') ?>)</option>
                            <option value="knot" <?= (isset($_SESSION['wind_unit']) && $_SESSION['wind_unit'] === 'knot') ? 'selected' : '' ?>>knot (<?= t('settings_wind_knot') ?>)</option>
                        </select>
                    </div>

                    <!-- Légnyomás -->
                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-3"><?= t('settings_pressure_unit') ?></label>
                        <select name="pressure_unit" class="w-full p-4 rounded-2xl border-2 border-slate-200 font-medium hover:border-blue-400 transition focus:outline-none focus:border-blue-600">
                            <option value="hpa" <?= (!isset($_SESSION['pressure_unit']) || $_SESSION['pressure_unit'] === 'hpa') ? 'selected' : '' ?>>hPa (hektopascal)</option>
                            <option value="mmhg" <?= (isset($_SESSION['pressure_unit']) && $_SESSION['pressure_unit'] === 'mmhg') ? 'selected' : '' ?>>mmHg (higanymilliméter)</option>
                            <option value="inhg" <?= (isset($_SESSION['pressure_unit']) && $_SESSION['pressure_unit'] === 'inhg') ? 'selected' : '' ?>>inHg (hüvelyk higanyszint)</option>
                            <option value="mbar" <?= (isset($_SESSION['pressure_unit']) && $_SESSION['pressure_unit'] === 'mbar') ? 'selected' : '' ?>>mbar (millibar)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] md:rounded-3xl p-6 md:p-8 mb-6 border border-slate-100 shadow-lg">
                <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-6 flex items-center gap-3 uppercase tracking-wider">
                    <i class="fa-solid fa-bell text-yellow-500"></i> <?= t('settings_notifications') ?>
                </h3>
                <div class="space-y-3 md:space-y-4">

                    <label class="flex items-center justify-between p-4 md:p-5 rounded-2xl hover:bg-slate-50 cursor-pointer transition group">
                        <div class="flex items-center gap-4">
                            <div class="bg-blue-100 p-3 rounded-xl group-hover:bg-blue-200 transition">
                                <i class="fa-solid fa-envelope text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm md:text-base"><?= t('settings_email_notif') ?></p>
                                <p class="text-xs text-slate-500"><?= t('settings_email_notif_desc') ?></p>
                            </div>
                        </div>
                        <input type="checkbox" name="notify_email" value="1"
                                <?= (isset($_SESSION['notify_email']) && $_SESSION['notify_email']) ? 'checked' : '' ?>
                               class="toggle w-14 h-7 rounded-full appearance-none bg-slate-300 checked:bg-blue-600 cursor-pointer transition">
                    </label>

                    <div class="flex items-center justify-between p-4 md:p-5 rounded-2xl under-development">
                        <div class="flex items-center gap-4">
                            <div class="bg-purple-100 p-3 rounded-xl">
                                <i class="fa-solid fa-mobile-screen-button text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm md:text-base">
                                    <?= t('settings_push_notif') ?>
                                    <span class="dev-badge">Készülőben</span>
                                </p>
                                <p class="text-xs text-slate-500">Böngésző értesítések extrém időjárásról</p>
                            </div>
                        </div>
                        <input type="checkbox" disabled
                               class="toggle w-14 h-7 rounded-full appearance-none bg-slate-300 cursor-not-allowed transition">
                    </div>

                    <label class="flex items-center justify-between p-4 md:p-5 rounded-2xl hover:bg-slate-50 cursor-pointer transition group">
                        <div class="flex items-center gap-4">
                            <div class="bg-orange-100 p-3 rounded-xl group-hover:bg-orange-200 transition">
                                <i class="fa-solid fa-triangle-exclamation text-orange-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm md:text-base"><?= t('settings_alert_notif') ?></p>
                                <p class="text-xs text-slate-500">Viharok, szélsőséges időjárás</p>
                            </div>
                        </div>
                        <input type="checkbox" name="notify_alerts" value="1"
                                <?= (!isset($_SESSION['notify_alerts']) || $_SESSION['notify_alerts']) ? 'checked' : '' ?>
                               class="toggle w-14 h-7 rounded-full appearance-none bg-slate-300 checked:bg-blue-600 cursor-pointer transition">
                    </label>

                    <div class="flex items-center justify-between p-4 md:p-5 rounded-2xl under-development">
                        <div class="flex items-center gap-4">
                            <div class="bg-green-100 p-3 rounded-xl">
                                <i class="fa-solid fa-newspaper text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-sm md:text-base">
                                    <?= t('settings_daily_notif') ?>
                                    <span class="dev-badge">Készülőben</span>
                                </p>
                                <p class="text-xs text-slate-500">Reggeli időjárás-összesítő emailben</p>
                            </div>
                        </div>
                        <input type="checkbox" disabled
                               class="toggle w-14 h-7 rounded-full appearance-none bg-slate-300 cursor-not-allowed transition">
                    </div>
                </div>
            </div>

            <!-- MEGJELENÉS -->
            <div class="bg-white rounded-3xl p-8 mb-6 border border-slate-100 shadow-lg">
                <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-palette text-pink-500"></i> <?= t('settings_appearance') ?>
                </h3>
                <div class="space-y-6">
                    <!-- Téma -->
                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-3"><?= t('settings_theme') ?></label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="theme" value="light"
                                        <?= (!isset($_SESSION['theme']) || $_SESSION['theme'] === 'light') ? 'checked' : '' ?>
                                       class="peer sr-only theme-radio">
                                <div class="p-5 rounded-2xl border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition hover:border-blue-400 text-center">
                                    <i class="fa-solid fa-sun text-4xl text-yellow-500 mb-2"></i>
                                    <p class="font-bold text-slate-900"><?= t('settings_theme_light') ?></p>
                                </div>
                                <i class="fa-solid fa-circle-check absolute top-3 right-3 text-xl text-blue-600 hidden peer-checked:block"></i>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="theme" value="dark"
                                        <?= (isset($_SESSION['theme']) && $_SESSION['theme'] === 'dark') ? 'checked' : '' ?>
                                       class="peer sr-only theme-radio">
                                <div class="p-5 rounded-2xl border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition hover:border-blue-400 text-center">
                                    <i class="fa-solid fa-moon text-4xl text-indigo-600 mb-2"></i>
                                    <p class="font-bold text-slate-900"><?= t('settings_theme_dark') ?></p>
                                </div>
                                <i class="fa-solid fa-circle-check absolute top-3 right-3 text-xl text-blue-600 hidden peer-checked:block"></i>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="theme" value="auto"
                                        <?= (isset($_SESSION['theme']) && $_SESSION['theme'] === 'auto') ? 'checked' : '' ?>
                                       class="peer sr-only theme-radio">
                                <div class="p-5 rounded-2xl border-2 border-slate-200 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition hover:border-blue-400 text-center">
                                    <i class="fa-solid fa-circle-half-stroke text-4xl text-slate-600 mb-2"></i>
                                    <p class="font-bold text-slate-900"><?= t('settings_theme_auto') ?></p>
                                </div>
                                <i class="fa-solid fa-circle-check absolute top-3 right-3 text-xl text-blue-600 hidden peer-checked:block"></i>
                            </label>
                        </div>
                    </div>

                    <!-- Animációk -->
                    <label class="flex items-center justify-between p-5 rounded-2xl hover:bg-slate-50 cursor-pointer transition group">
                        <div class="flex items-center gap-4">
                            <div class="bg-cyan-100 p-3 rounded-xl group-hover:bg-cyan-200 transition">
                                <i class="fa-solid fa-wand-magic-sparkles text-cyan-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900"><?= t('settings_animations') ?></p>
                                <p class="text-sm text-slate-500"><?= t('settings_animations_desc') ?></p>
                            </div>
                        </div>
                        <input type="checkbox" name="animations" value="1"
                                <?= (!isset($_SESSION['animations']) || $_SESSION['animations']) ? 'checked' : '' ?>
                               class="toggle w-14 h-7 rounded-full appearance-none bg-slate-300 checked:bg-blue-600 cursor-pointer transition">
                    </label>
                </div>
            </div>

            <!-- NYELV ÉS RÉGIÓ -->
            <div class="bg-white rounded-3xl p-8 mb-6 border border-slate-100 shadow-lg">
                <h3 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-globe text-blue-500"></i> <?= t('settings_language') ?>
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-3"><?= t('settings_language') ?></label>
                        <select name="language" class="w-full p-4 rounded-2xl border-2 border-slate-200 font-medium hover:border-blue-400 transition focus:outline-none focus:border-blue-600">
                            <option value="hu" <?= (!isset($_SESSION['language']) || $_SESSION['language'] === 'hu' || (isset($_COOKIE['language']) && $_COOKIE['language'] === 'hu')) ? 'selected' : '' ?>>🇭🇺 Magyar</option>
                            <option value="en" <?= ((isset($_SESSION['language']) && $_SESSION['language'] === 'en') || (isset($_COOKIE['language']) && $_COOKIE['language'] === 'en')) ? 'selected' : '' ?>>🇬🇧 English</option>
                            <option value="sr" <?= ((isset($_SESSION['language']) && $_SESSION['language'] === 'sr') || (isset($_COOKIE['language']) && $_COOKIE['language'] === 'sr')) ? 'selected' : '' ?>>🇷🇸 Српски</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-600 mb-3"><?= t('settings_timezone') ?></label>
                        <select name="timezone" class="w-full p-4 rounded-2xl border-2 border-slate-200 font-medium hover:border-blue-400 transition focus:outline-none focus:border-blue-600">
                            <option value="Europe/Budapest" <?= (!isset($_SESSION['timezone']) || $_SESSION['timezone'] === 'Europe/Budapest') ? 'selected' : '' ?>>Europe/Budapest (CET)</option>
                            <option value="Europe/London" <?= (isset($_SESSION['timezone']) && $_SESSION['timezone'] === 'Europe/London') ? 'selected' : '' ?>>Europe/London (GMT)</option>
                            <option value="America/New_York" <?= (isset($_SESSION['timezone']) && $_SESSION['timezone'] === 'America/New_York') ? 'selected' : '' ?>>America/New_York (EST)</option>
                            <option value="Asia/Tokyo" <?= (isset($_SESSION['timezone']) && $_SESSION['timezone'] === 'Asia/Tokyo') ? 'selected' : '' ?>>Asia/Tokyo (JST)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- MENTÉS GOMB -->
            <button type="submit" class="w-full bg-blue-600 text-white py-5 rounded-2xl font-black text-lg hover:bg-blue-700 transition shadow-2xl hover:shadow-blue-500/50 hover:scale-[1.02]">
                <i class="fa-solid fa-floppy-disk mr-2"></i> <?= t('settings_save_btn') ?>
            </button>

            <p class="text-center text-sm text-slate-500 mt-4">
                <i class="fa-solid fa-info-circle mr-1"></i> <?= t('settings_save_note') ?>
            </p>

        </div>
    </form>
    <?php include __DIR__ . '/mobile_nav.php'; ?>
</main>

<script>
    // Jelzés ha módosítottál valamit
    let hasChanges = false;

    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('change', function() {
            hasChanges = true;
        });
    });

    // --- ÚJ RÉSZ: PUSH ÉRTESÍTÉS ENGEDÉLYEZÉSE ---
    const pushToggle = document.querySelector('input[name="notify_push"]');
    if (pushToggle) {
        pushToggle.addEventListener('change', function() {
            if (this.checked) {
                // Megkérdezzük a böngészőtől, szabad-e értesítést küldeni
                if (!("Notification" in window)) {
                    alert("Ez a böngésző nem támogatja az értesítéseket.");
                    this.checked = false;
                    return;
                }

                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        // Siker! Küldünk egy teszt üzenetet
                        new Notification("WeatherBase", {
                            body: "Az értesítések sikeresen engedélyezve!",
                            icon: "https://cdn-icons-png.flaticon.com/512/5551/5551284.png"
                        });
                    } else {
                        // Ha elutasította, visszakapcsoljuk a gombot
                        alert("Az értesítésekhez engedélyezned kell azt a böngészőben!");
                        this.checked = false;
                    }
                });
            }
        });
    }
    // --- ÚJ RÉSZ VÉGE ---

    // Figyelmeztetés ha el akarsz navigálni mentés nélkül
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Mentés után ne figyelmeztessen
    document.querySelector('form').addEventListener('submit', function() {
        hasChanges = false;
    });
</script>

</body>
</html>