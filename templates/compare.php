<?php
include __DIR__ . '/lang.php';
// $compareData és $error már át van adva az index.php-ból!
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= t('nav_compare') ?> - <?= t('app_name') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 flex min-h-screen text-slate-800">

<?php include __DIR__ . '/sidebar_smart.php'; ?>

<main class="flex-1 p-4 md:p-10 pb-32 md:pb-10 w-full overflow-x-hidden">
    <header class="mb-10">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 flex items-center gap-3">
            <i class="fa-solid fa-code-compare text-green-500"></i> <?= t('compare_title') ?>
        </h1>
        <p class="text-slate-500 italic text-sm md:text-base"><?= t('compare_subtitle') ?></p>
    </header>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-2xl mb-6">
            <p class="font-bold"><i class="fa-solid fa-exclamation-triangle mr-2"></i><?= $error ?></p>
        </div>
    <?php endif; ?>

    <form method="GET" action="/iws-2025-hu/Projekt-iws/public/compare" class="bg-white rounded-3xl p-6 md:p-8 mb-8 border border-slate-100 shadow-sm">
        <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-blue-600"></i> <?= t('compare_select_cities') ?>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-600 mb-2">1. <?= t('compare_city') ?></label>
                <input type="text" name="city1" value="<?= htmlspecialchars($_GET['city1'] ?? '') ?>"
                       placeholder="pl. Budapest"
                       class="w-full p-4 rounded-2xl border-2 border-slate-200 font-medium hover:border-blue-400 focus:border-blue-600 focus:outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-600 mb-2">2. <?= t('compare_city') ?></label>
                <input type="text" name="city2" value="<?= htmlspecialchars($_GET['city2'] ?? '') ?>"
                       placeholder="pl. Párizs"
                       class="w-full p-4 rounded-2xl border-2 border-slate-200 font-medium hover:border-blue-400 focus:border-blue-600 focus:outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-600 mb-2">3. <?= t('compare_city') ?></label>
                <input type="text" name="city3" value="<?= htmlspecialchars($_GET['city3'] ?? '') ?>"
                       placeholder="pl. Krakkó"
                       class="w-full p-4 rounded-2xl border-2 border-slate-200 font-medium hover:border-blue-400 focus:border-blue-600 focus:outline-none transition">
            </div>
        </div>
        <button type="submit" class="mt-6 w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg">
            <i class="fa-solid fa-magnifying-glass"></i> <?= t('compare_btn') ?>
        </button>
    </form>

    <?php if (!empty($compareData)): ?>

        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-lg mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-6">
                <h3 class="text-2xl font-black text-white flex items-center gap-3">
                    <i class="fa-solid fa-table"></i> <?= t('compare_comparison_table') ?>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]">
                    <thead class="bg-slate-50 border-b-2 border-slate-200">
                    <tr>
                        <th class="p-6 text-left font-black text-slate-700 uppercase tracking-wider"><?= t('compare_weather_condition') ?></th>
                        <?php
                        $colors = ['red', 'blue', 'green'];
                        foreach ($compareData as $i => $city):
                            ?>
                            <th class="p-6 text-center font-black text-slate-700 uppercase tracking-wider">
                                <i class="fa-solid fa-location-dot text-<?= $colors[$i] ?>-500 mr-2"></i><?= htmlspecialchars($city['name']) ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    <tr class="hover:bg-blue-50 transition">
                        <td class="p-6 font-bold text-slate-700 flex items-center gap-3 text-sm italic">
                            <i class="fa-solid fa-temperature-high text-red-500"></i> <?= t('weather_real_temp') ?>
                        </td>
                        <?php foreach ($compareData as $city): ?>
                            <td class="p-6 text-center">
                                <span class="text-2xl md:text-3xl font-black text-slate-900"><?= round($city['temp']) ?>°C</span>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr class="hover:bg-orange-50 transition">
                        <td class="p-6 font-bold text-slate-700 flex items-center gap-3 text-sm italic">
                            <i class="fa-solid fa-temperature-half text-orange-500"></i> <?= t('weather_feels_like') ?>
                        </td>
                        <?php foreach ($compareData as $city): ?>
                            <td class="p-6 text-center text-lg md:text-xl font-bold text-slate-700"><?= round($city['feels_like']) ?>°C</td>
                        <?php endforeach; ?>
                    </tr>
                    <tr class="hover:bg-blue-50 transition">
                        <td class="p-6 font-bold text-slate-700 flex items-center gap-3 text-sm italic">
                            <i class="fa-solid fa-droplet text-blue-500"></i> <?= t('weather_humidity') ?>
                        </td>
                        <?php foreach ($compareData as $city): ?>
                            <td class="p-6 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-xl md:text-2xl font-black text-slate-900"><?= $city['humidity'] ?>%</span>
                                    <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <?php $width = $city['humidity']; ?>
                                        <div class="bg-blue-500 h-full" style="width: <?php echo $width; ?>%"></div>
                                    </div>
                                </div>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr class="hover:bg-cyan-50 transition">
                        <td class="p-6 font-bold text-slate-700 flex items-center gap-3 text-sm italic">
                            <i class="fa-solid fa-wind text-cyan-500"></i> <?= t('weather_wind') ?>
                        </td>
                        <?php foreach ($compareData as $city): ?>
                            <td class="p-6 text-center font-bold text-slate-700"><?= round($city['wind_speed'], 1) ?> m/s</td>
                        <?php endforeach; ?>
                    </tr>
                    <tr class="hover:bg-purple-50 transition">
                        <td class="p-6 font-bold text-slate-700 flex items-center gap-3 text-sm italic">
                            <i class="fa-solid fa-gauge-high text-purple-500"></i> <?= t('weather_pressure') ?>
                        </td>
                        <?php foreach ($compareData as $city): ?>
                            <td class="p-6 text-center font-bold text-slate-700"><?= $city['pressure'] ?> hPa</td>
                        <?php endforeach; ?>
                    </tr>
                    <tr class="hover:bg-green-50 transition">
                        <td class="p-6 font-bold text-slate-700 flex items-center gap-3 text-sm italic">
                            <i class="fa-solid fa-cloud text-green-500"></i> <?= t('archive_description') ?>
                        </td>
                        <?php foreach ($compareData as $city): ?>
                            <td class="p-6 text-center">
                                <img src="https://openweathermap.org/img/wn/<?= $city['icon'] ?>@2x.png" alt="" class="inline-block w-12 h-12">
                                <p class="text-xs text-slate-500"><?= htmlspecialchars($city['description']) ?></p>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        $hottestIndex = array_search(max(array_column($compareData, 'temp')), array_column($compareData, 'temp'));
        $coldestIndex = array_search(min(array_column($compareData, 'temp')), array_column($compareData, 'temp'));
        $humidestIndex = array_search(max(array_column($compareData, 'humidity')), array_column($compareData, 'humidity'));
        ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-red-50 to-orange-50 dark:from-red-900 dark:to-orange-900 rounded-3xl p-8 border border-red-100 dark:border-red-500">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-black text-slate-900 dark:text-white text-lg">🔥 <?= t('compare_hottest') ?></h4>
                    <i class="fa-solid fa-trophy text-3xl text-yellow-500"></i>
                </div>
                <p class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-2"><?= htmlspecialchars($compareData[$hottestIndex]['name']) ?></p>
                <p class="text-xl text-slate-600 dark:text-slate-300"><?= round($compareData[$hottestIndex]['temp']) ?>°C</p>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900 dark:to-cyan-900 rounded-3xl p-8 border border-blue-100 dark:border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-black text-slate-900 dark:text-white text-lg">❄️ <?= t('compare_coldest') ?></h4>
                    <i class="fa-solid fa-snowflake text-3xl text-blue-500"></i>
                </div>
                <p class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-2"><?= htmlspecialchars($compareData[$coldestIndex]['name']) ?></p>
                <p class="text-xl text-slate-600 dark:text-slate-300"><?= round($compareData[$coldestIndex]['temp']) ?>°C</p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900 rounded-3xl p-8 border border-green-100 dark:border-green-500">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-black text-slate-900 dark:text-white text-lg">💧 <?= t('compare_most_humid') ?></h4>
                    <i class="fa-solid fa-droplet text-3xl text-green-500"></i>
                </div>
                <p class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-2"><?= htmlspecialchars($compareData[$humidestIndex]['name']) ?></p>
                <p class="text-xl text-slate-600 dark:text-slate-300"><?= $compareData[$humidestIndex]['humidity'] ?>%</p>
            </div>
        </div>

    <?php else: ?>
        <div class="bg-white rounded-3xl p-16 text-center border border-slate-100 shadow-sm">
            <i class="fa-solid fa-magnifying-glass text-blue-500 text-8xl mb-6"></i>
            <h2 class="text-2xl font-bold text-slate-400"><?= t('compare_select_cities') ?></h2>
        </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/mobile_nav.php'; ?>

</body>
</html>