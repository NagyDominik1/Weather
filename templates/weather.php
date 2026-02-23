<?php include __DIR__ . '/lang.php'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('app_name') ?> - <?= htmlspecialchars($viewData['city']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Időjárás alapú dinamikus háttér színek */
        <?php
        $weatherId = $viewData['data']['weather'][0]['id'] ?? 800;
        $isNight = date('H') >= 18 || date('H') < 6;

        // Színek időjárás szerint
        if ($weatherId >= 200 && $weatherId < 300) {
            // Vihar
            $gradient = $isNight ? 'from-slate-900 via-purple-900 to-slate-800' : 'from-slate-700 via-purple-700 to-slate-600';
        } elseif ($weatherId >= 300 && $weatherId < 600) {
            // Eső/Szitálás
            $gradient = $isNight ? 'from-slate-800 via-blue-900 to-slate-700' : 'from-blue-600 via-slate-600 to-blue-500';
        } elseif ($weatherId >= 600 && $weatherId < 700) {
            // Hó
            $gradient = 'from-cyan-100 via-blue-200 to-slate-300';
        } elseif ($weatherId >= 700 && $weatherId < 800) {
            // Köd/Pára
            $gradient = 'from-slate-400 via-gray-300 to-slate-400';
        } elseif ($weatherId == 800) {
            // Tiszta égbolt
            $gradient = $isNight ? 'from-indigo-950 via-purple-900 to-blue-950' : 'from-blue-400 via-cyan-300 to-blue-500';
        } else {
            // Felhős
            $gradient = $isNight ? 'from-slate-700 via-blue-800 to-slate-600' : 'from-blue-500 via-slate-400 to-blue-600';
        }
        ?>

        /* Animált részecskék */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        @keyframes rain {
            0% { transform: translateY(-100vh) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(100vh) translateX(50px); opacity: 0; }
        }

        @keyframes snow {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }

        @keyframes shine {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        .rain-drop {
            position: absolute;
            width: 2px;
            height: 20px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.6));
            animation: rain 1s linear infinite;
        }

        .snow-flake {
            position: absolute;
            color: white;
            animation: snow 8s linear infinite;
        }

        .sun-ray {
            position: absolute;
            animation: shine 3s ease-in-out infinite;
        }

        /* Smooth betöltés */
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Szél irány mutató */
        .wind-arrow {
            transition: transform 0.5s ease;
        }

        /* Progressbar animáció */
        .progress-bar {
            transition: width 1.5s ease-out;
        }
        @media (max-width: 768px) {
            /* Viewport fix - ne csússzon ki */
            body {
                overflow-x: hidden;
            }

            /* A fő tartalom ne csússzon ki a képernyőről */
            .max-w-6xl {
                max-width: 100%;
                width: 100%;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            /* Main padding fix */
            main {
                padding: 0.5rem !important;
            }

            /* A főkártya mobilra optimalizálva */
            .bg-gradient-to-r {
                padding: 1.5rem !important;
                border-radius: 1.5rem !important;
                margin-bottom: 1rem !important;
            }

            /* Városnév kisebb mobilon */
            .text-6xl {
                font-size: 2.5rem !important;
            }

            /* Hőmérséklet kisebb mobilon */
            .text-9xl {
                font-size: 4rem !important;
            }

            /* Kártyák padding fix */
            .p-8 {
                padding: 1rem !important;
            }

            .p-10 {
                padding: 1.5rem !important;
            }

            .p-12 {
                padding: 1.5rem !important;
            }

            /* Grid fix - egysoros elrendezés mobilon */
            .grid-cols-2 {
                grid-template-columns: 1fr !important;
            }

            .md\:grid-cols-2, .md\:grid-cols-3, .md\:grid-cols-5 {
                grid-template-columns: 1fr !important;
            }

            /* Gap kisebbítése mobilon */
            .gap-6 {
                gap: 0.75rem !important;
            }

            /* Text size fix */
            .text-4xl {
                font-size: 2rem !important;
            }

            .text-3xl {
                font-size: 1.5rem !important;
            }

            .text-2xl {
                font-size: 1.25rem !important;
            }

            .text-xl {
                font-size: 1rem !important;
            }

            /* Rounded corners kisebbek mobilon */
            .rounded-3xl {
                border-radius: 1.5rem !important;
            }

            .rounded-\[3rem\] {
                border-radius: 1.5rem !important;
            }

            .rounded-\[2\.5rem\] {
                border-radius: 1.5rem !important;
            }

            .rounded-\[2rem\] {
                border-radius: 1rem !important;
            }

            /* Sidebar fix - ne csússzon ki */
            aside {
                position: fixed;
                z-index: 50;
            }

            /* Fade-in margin fix */
            .fade-in {
                margin: 0 auto;
                width: 100%;
            }

            /* Icon size fix */
            .text-\[20rem\] {
                font-size: 8rem !important;
            }

            .text-\[10rem\] {
                font-size: 5rem !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

<!-- SIDEBAR -->
<?php include __DIR__ . '/sidebar_smart.php'; ?>
<?php include __DIR__ . '/dark-mode-styles.php'; ?>

<main class="flex-1 p-6 md:p-10 flex flex-col items-center">
    <div class="max-w-6xl w-full">

        <!-- FŐKÁRTYA - Dinamikus háttér + Animáció -->
        <div class="bg-gradient-to-r <?= $gradient ?> rounded-[3rem] p-12 text-white shadow-2xl relative overflow-hidden mb-8 fade-in">

            <!-- Időjárás alapú animációk -->
            <?php if ($weatherId >= 200 && $weatherId < 300): ?>
                <!-- Vihar - villámlás effekt -->
                <div class="absolute inset-0 opacity-20" id="lightning"></div>
            <?php elseif ($weatherId >= 300 && $weatherId < 600): ?>
                <!-- Eső -->
                <?php for ($i = 0; $i < 50; $i++): ?>
                    <div class="rain-drop" style="left: <?= rand(0, 100) ?>%; animation-delay: <?= rand(0, 1000) ?>ms; animation-duration: <?= rand(800, 1200) ?>ms;"></div>
                <?php endfor; ?>
            <?php elseif ($weatherId >= 600 && $weatherId < 700): ?>
                <!-- Hó -->
                <?php for ($i = 0; $i < 30; $i++): ?>
                    <div class="snow-flake" style="left: <?= rand(0, 100) ?>%; top: -50px; animation-delay: <?= rand(0, 5000) ?>ms; font-size: <?= rand(10, 20) ?>px;">❄</div>
                <?php endfor; ?>
            <?php elseif ($weatherId == 800 && !$isNight): ?>
                <!-- Napfény -->
                <div class="sun-ray absolute top-10 right-10 text-yellow-200 text-9xl">
                    <i class="fa-solid fa-sun"></i>
                </div>
            <?php endif; ?>

            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center text-center md:text-left">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <div class="flex items-center gap-4">
                        <h1 class="text-6xl font-black tracking-tight uppercase"><?= htmlspecialchars($viewData['city']) ?></h1>

                        <!-- KEDVENC CSILLAG -->
                        <?php if (isset($viewData['city_id'])): ?>
                            <form action="/iws-2025-hu/Projekt-iws/public/favorite/<?= $viewData['is_favorite'] ? 'remove' : 'add' ?>" method="post">
                                <input type="hidden" name="city_id" value="<?= $viewData['city_id'] ?>">
                                <button type="submit" class="hover:scale-125 transition-transform duration-200 cursor-pointer">
                                    <?php if ($viewData['is_favorite']): ?>
                                        <i class="fa-solid fa-star text-4xl text-yellow-400 drop-shadow-md"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-star text-4xl text-white/60 hover:text-white"></i>
                                    <?php endif; ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <p class="text-white/90 text-2xl font-medium capitalize italic">
                        <?= htmlspecialchars($viewData['data']['weather'][0]['description'] ?? 'Unknown') ?>
                    </p>
                </div>
                <div class="text-9xl font-black mt-8 md:mt-0 tracking-tighter">
                    <?php
                    // Hőmérséklet konverzió
                    $temp = $viewData['data']['main']['temp'];
                    $tempUnit = $_SESSION['temp_unit'] ?? 'celsius';

                    if ($tempUnit === 'fahrenheit') {
                        $displayTemp = round(($temp * 9/5) + 32);
                        $unitSymbol = 'F';
                    } else {
                        $displayTemp = round($temp);
                        $unitSymbol = 'C';
                    }
                    ?>
                    <?= $displayTemp ?>°<?= $unitSymbol ?>
                </div>
            </div>
            <i class="fa-solid fa-cloud-sun absolute -bottom-10 -right-10 text-[20rem] text-white/10"></i>
        </div>

        <!-- Hőérzet vs Valós hőmérséklet -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 fade-in">
            <?php
            // Hőmérséklet beállítás
            $tempUnit = $_SESSION['temp_unit'] ?? 'celsius';
            $realTemp = $viewData['data']['main']['temp'];
            $feelsLike = $viewData['data']['main']['feels_like'];

            if ($tempUnit === 'fahrenheit') {
                $realTempDisplay = round(($realTemp * 9/5) + 32);
                $feelsLikeDisplay = round(($feelsLike * 9/5) + 32);
                $unitText = '°F';
            } else {
                $realTempDisplay = round($realTemp);
                $feelsLikeDisplay = round($feelsLike);
                $unitText = '°C';
            }

            $diff = abs($realTempDisplay - $feelsLikeDisplay);
            ?>

            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-red-100 dark:bg-red-900 p-4 rounded-2xl">
                        <i class="fa-solid fa-temperature-high text-3xl text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider"><?= t('weather_real_temp') ?></p>
                        <p class="text-4xl font-black text-slate-900 dark:text-white"><?= $realTempDisplay ?><?= $unitText ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-orange-100 dark:bg-orange-900 p-4 rounded-2xl">
                        <i class="fa-solid fa-temperature-half text-3xl text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider"><?= t('weather_feels_like') ?></p>
                        <p class="text-4xl font-black text-slate-900 dark:text-white"><?= $feelsLikeDisplay ?><?= $unitText ?></p>
                    </div>
                </div>
                <?php if ($diff > 2): ?>
                    <p class="text-sm text-orange-600 dark:text-orange-400 font-semibold mt-2">
                        <i class="fa-solid fa-circle-exclamation"></i> <?= $diff ?>° <?= t('weather_temp_diff') ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- KÖVETKEZŐ NAPOK -->
        <div class="mt-12 mb-12 fade-in">
            <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
                <i class="fa-solid fa-calendar-days text-blue-600"></i> <?= t('weather_next_days') ?>
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php if (!empty($viewData['forecast'])): ?>
                    <?php
                    $tempUnit = $_SESSION['temp_unit'] ?? 'celsius';
                    foreach ($viewData['forecast'] as $day):
                        // Konverzió
                        if ($tempUnit === 'fahrenheit') {
                            $forecastTemp = round(($day['temp'] * 9/5) + 32);
                            $unitSymbol = '°F';
                        } else {
                            $forecastTemp = $day['temp'];
                            $unitSymbol = '°';
                        }
                        ?>
                        <div class="bg-white dark:bg-slate-800 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm text-center hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2"><?= $day['date'] ?></p>
                            <div class="bg-blue-50 dark:bg-blue-900 w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-3 group-hover:bg-blue-100 dark:group-hover:bg-blue-800 transition-colors">
                                <img src="http://openweathermap.org/img/wn/<?= $day['icon'] ?>@2x.png" class="w-12 h-12" alt="weather icon">
                            </div>
                            <p class="text-2xl font-black text-slate-800 dark:text-white"><?= $forecastTemp ?><?= $unitSymbol ?></p>
                            <p class="text-[10px] text-blue-500 dark:text-blue-400 font-bold uppercase mt-2 leading-tight"><?= $day['desc'] ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-slate-400 italic"><?= t('weather_forecast_unavailable') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- RÉSZLETES ADATOK - 3 oszlopos rács -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 fade-in">

            <!-- Páratartalom + Szél -->
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-chart-simple text-blue-600"></i> <?= t('weather_basic_data') ?>
                </h3>
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400 font-medium flex items-center gap-2">
                            <i class="fa-solid fa-droplet text-blue-500"></i> <?= t('weather_humidity') ?>
                        </span>
                        <span class="text-2xl font-black text-slate-900 dark:text-white"><?= $viewData['data']['main']['humidity'] ?>%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-700 h-2 rounded-full overflow-hidden">
                        <div class="progress-bar bg-blue-500 h-full rounded-full" style="width: <?= $viewData['data']['main']['humidity'] ?>%"></div>
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <span class="text-slate-600 dark:text-slate-400 font-medium flex items-center gap-2">
                            <i class="fa-solid fa-wind text-cyan-500"></i> <?= t('weather_wind') ?>
                        </span>
                        <?php
                        // Szélsebesség konverzió
                        $windSpeed = $viewData['data']['wind']['speed'];
                        $windUnit = $_SESSION['wind_unit'] ?? 'ms';

                        switch ($windUnit) {
                            case 'kmh':
                                $displayWind = round($windSpeed * 3.6, 1);
                                $windLabel = 'km/h';
                                break;
                            case 'mph':
                                $displayWind = round($windSpeed * 2.237, 1);
                                $windLabel = 'mph';
                                break;
                            case 'knot':
                                $displayWind = round($windSpeed * 1.944, 1);
                                $windLabel = 'knot';
                                break;
                            default: // ms
                                $displayWind = round($windSpeed, 1);
                                $windLabel = 'm/s';
                        }
                        ?>
                        <span class="text-2xl font-black text-slate-900 dark:text-white"><?= $displayWind ?> <?= $windLabel ?></span>
                    </div>

                    <!-- Szél irány mutató -->
                    <?php if (isset($viewData['data']['wind']['deg'])): ?>
                        <div class="flex items-center gap-3 mt-2">
                            <div class="w-12 h-12 bg-cyan-50 dark:bg-cyan-900 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-arrow-up text-cyan-600 dark:text-cyan-400 wind-arrow" style="transform: rotate(<?= $viewData['data']['wind']['deg'] ?>deg)"></i>
                            </div>
                            <span class="text-sm text-slate-500 dark:text-slate-400"><?= $viewData['data']['wind']['deg'] ?>°</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Légnyomás + Látótávolság -->
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-gauge-high text-purple-600"></i> <?= t('weather_atmosphere') ?>
                </h3>
                <div class="space-y-6">
                    <div>
                        <?php
                        // Légnyomás konverzió
                        $pressure = $viewData['data']['main']['pressure'];
                        $pressureUnit = $_SESSION['pressure_unit'] ?? 'hpa';

                        switch ($pressureUnit) {
                            case 'mmhg':
                                $displayPressure = round($pressure * 0.750062, 1);
                                $pressureLabel = 'mmHg';
                                break;
                            case 'inhg':
                                $displayPressure = round($pressure * 0.02953, 2);
                                $pressureLabel = 'inHg';
                                break;
                            case 'mbar':
                                $displayPressure = round($pressure, 1);
                                $pressureLabel = 'mbar';
                                break;
                            default: // hpa
                                $displayPressure = round($pressure);
                                $pressureLabel = 'hPa';
                        }
                        ?>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-slate-600 dark:text-slate-400 font-medium"><?= t('weather_pressure') ?></span>
                            <span class="text-2xl font-black text-slate-900 dark:text-white"><?= $displayPressure ?> <?= $pressureLabel ?></span>
                        </div>
                        <!-- Barométer vizualizáció -->
                        <div class="relative w-full h-3 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <?php
                            $pressurePercent = (($pressure - 950) / 100) * 100;
                            $pressurePercent = max(0, min(100, $pressurePercent));
                            ?>
                            <div class="absolute h-full bg-gradient-to-r from-blue-400 via-green-400 to-red-400 rounded-full" style="width: <?= $pressurePercent ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400 mt-1">
                            <span><?= t('weather_pressure_low') ?></span>
                            <span><?= t('weather_pressure_normal') ?></span>
                            <span><?= t('weather_pressure_high') ?></span>
                        </div>
                    </div>

                    <?php if (isset($viewData['data']['visibility'])): ?>
                        <div class="flex justify-between items-center mt-6">
                            <span class="text-slate-600 dark:text-slate-400 font-medium flex items-center gap-2">
                                <i class="fa-solid fa-eye text-indigo-500"></i> <?= t('weather_visibility') ?>
                            </span>
                            <span class="text-2xl font-black text-slate-900 dark:text-white"><?= round($viewData['data']['visibility'] / 1000, 1) ?> km</span>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($viewData['data']['clouds']['all'])): ?>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-slate-600 dark:text-slate-400 font-medium flex items-center gap-2">
                                <i class="fa-solid fa-cloud text-slate-400"></i> <?= t('weather_clouds') ?>
                            </span>
                            <span class="text-2xl font-black text-slate-900 dark:text-white"><?= $viewData['data']['clouds']['all'] ?>%</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Napkelte / Napnyugta -->
            <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-sun text-yellow-500"></i> <?= t('weather_sun') ?>
                </h3>
                <div class="space-y-8">
                    <?php if (isset($viewData['data']['sys']['sunrise'])): ?>
                        <div class="flex items-center gap-4">
                            <div class="bg-orange-100 dark:bg-orange-900 p-4 rounded-2xl">
                                <i class="fa-solid fa-sun-plant-wilt text-2xl text-orange-600 dark:text-orange-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase"><?= t('weather_sunrise') ?></p>
                                <p class="text-3xl font-black text-slate-900 dark:text-white"><?= date('H:i', $viewData['data']['sys']['sunrise']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($viewData['data']['sys']['sunset'])): ?>
                        <div class="flex items-center gap-4">
                            <div class="bg-indigo-100 dark:bg-indigo-900 p-4 rounded-2xl">
                                <i class="fa-solid fa-moon text-2xl text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase"><?= t('weather_sunset') ?></p>
                                <p class="text-3xl font-black text-slate-900 dark:text-white"><?= date('H:i', $viewData['data']['sys']['sunset']) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    if (isset($viewData['data']['sys']['sunrise']) && isset($viewData['data']['sys']['sunset'])):
                        $dayLength = $viewData['data']['sys']['sunset'] - $viewData['data']['sys']['sunrise'];
                        $hours = floor($dayLength / 3600);
                        $minutes = floor(($dayLength % 3600) / 60);
                        ?>
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase mb-1"><?= t('weather_day_length') ?></p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white"><?= $hours ?>h <?= $minutes ?>m</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ÖLTÖZKÖDÉSI JAVASLAT -->
        <div class="bg-white dark:bg-slate-800 p-10 rounded-[2.5rem] shadow-lg border border-slate-100 dark:border-slate-700 mb-8 fade-in">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3 uppercase tracking-wider">
                <i class="fa-solid fa-shirt text-orange-500"></i> <?= t('weather_outfit_title') ?>
            </h3>
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900 dark:to-amber-900 p-10 rounded-3xl border border-orange-100 dark:border-orange-700 min-h-[120px] flex items-center">
                <p class="text-orange-900 dark:text-orange-100 text-2xl font-medium leading-relaxed italic">
                    "<?= htmlspecialchars($viewData['recommendation'] ?? t('weather_outfit_no_recommendation')) ?>"
                </p>
            </div>
            <div class="mt-8 flex justify-between items-center">
                <a href="/iws-2025-hu/Projekt-iws/public/" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold hover:gap-4 transition-all uppercase text-sm tracking-widest">
                    <i class="fa-solid fa-arrow-left"></i> <?= t('weather_back_to_search') ?>
                </a>
                <a href="/iws-2025-hu/Projekt-iws/public/archive?city_id=<?= $viewData['city_id'] ?? '' ?>" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 font-bold hover:text-blue-600 dark:hover:text-blue-400 transition uppercase text-sm tracking-widest">
                    <?= t('weather_view_archive') ?> <i class="fa-solid fa-clock-rotate-left"></i>
                </a>
            </div>
        </div>

    </div>
</main>

<script>
    // Villámlás effekt vihar esetén
    <?php if ($weatherId >= 200 && $weatherId < 300): ?>
    function lightning() {
        const flash = document.getElementById('lightning');
        flash.style.background = 'white';
        setTimeout(() => flash.style.background = 'transparent', 100);
        setTimeout(() => {
            flash.style.background = 'white';
            setTimeout(() => flash.style.background = 'transparent', 100);
        }, 200);
    }
    setInterval(lightning, 5000 + Math.random() * 5000);
    <?php endif; ?>

    // Progress bar animáció
    window.addEventListener('load', () => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => bar.style.width = width, 100);
        });
    });
</script>

</body>
</html>