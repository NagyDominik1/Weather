<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/lang.php';
require_once __DIR__ . '/../helpers/CityTranslator.php';
require_once __DIR__ . '/../src/Database.php';

$currentLang = $_COOKIE['language'] ?? $_SESSION['language'] ?? 'hu';
$basePath = rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
$userId = $_SESSION['user_id'] ?? null;

$history = [];

if ($userId) {
    try {
        $database = new Database();
        $pdo = $database->getConnection();

        // --- AUTOMATIKUS TAKARÍTÁS (7 nap) ---
        $deleteOld = $pdo->prepare("DELETE FROM weather_data WHERE dt < DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $deleteOld->execute();

        // --- SZŰRÉS ÉS LEKÉRDEZÉS ---
        $query = "SELECT wd.*, c.city_name 
                  FROM weather_data wd
                  JOIN cities c ON wd.city_id = c.id
                  WHERE wd.user_id = :user_id";

        $params = [':user_id' => $userId];

        // VÁROS KERESÉSE ÍRÁSSAL (Név alapján)
        if (!empty($_GET['city_search'])) {
            $query .= " AND c.city_name LIKE :city_search";
            $params[':city_search'] = '%' . $_GET['city_search'] . '%';
        }

        // Dátum szűrés
        if (!empty($_GET['date_from'])) {
            $query .= " AND DATE(wd.dt) = :date_from";
            $params[':date_from'] = $_GET['date_from'];
        }

        $query .= " ORDER BY wd.dt DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        error_log("Hiba: " . $e->getMessage());
    }
}

if (!function_exists('getCityDisplayName')) {
    function getCityDisplayName($name, $lang) {
        return CityTranslator::translate($name, $lang);
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= t('nav_archive') ?> - <?= t('app_name') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 flex min-h-screen text-slate-800">

<?php include __DIR__ . '/sidebar_smart.php'; ?>

<main class="flex-1 p-4 md:p-10 pb-32 md:pb-10 w-full overflow-x-hidden">
    <header class="mb-6 md:mb-10">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 flex items-center gap-3">
            <i class="fa-solid fa-clock-rotate-left text-purple-600"></i> <?= t('archive_title') ?>
        </h1>
        <p class="text-slate-500 italic text-sm md:text-base">
            <?php if ($userId): ?>
                <?= t('archive_subtitle') ?> (<strong><?= htmlspecialchars($_SESSION['email'] ?? 'User') ?></strong>)
            <?php else: ?>
                Kérjük, jelentkezzen be az adatok megtekintéséhez.
            <?php endif; ?>
        </p>
    </header>

    <?php if ($userId): ?>
        <div class="bg-white rounded-3xl p-6 md:p-8 mb-8 border border-slate-100 shadow-sm">
            <form method="get" action="" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2"><?= t('archive_city') ?></label>
                    <div class="relative">
                        <i class="fa-solid fa-city absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="city_search"
                               value="<?= htmlspecialchars($_GET['city_search'] ?? '') ?>"
                               placeholder="Pl. Budapest..."
                               class="w-full pl-12 p-4 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-purple-500 outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2"><?= t('archive_date') ?></label>
                    <input type="date" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>"
                           class="w-full p-4 rounded-2xl border-2 border-slate-200 font-medium text-sm focus:border-purple-500 outline-none">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-4 rounded-2xl font-bold shadow-lg shadow-purple-200 transition-all active:scale-95">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i> <?= t('archive_search_btn') ?>
                    </button>
                </div>
            </form>
        </div>

        <?php if (!empty($history)): ?>
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-lg">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-5">
                    <h3 class="text-xl font-black text-white">
                        <?= t('archive_results') ?> (<?= count($history) ?>)
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px]">
                        <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="p-4 text-left text-xs uppercase text-slate-500"><?= t('archive_city') ?></th>
                            <th class="p-4 text-center text-xs uppercase text-slate-500"><?= t('archive_temperature') ?></th>
                            <th class="p-4 text-center text-xs uppercase text-slate-500 hidden md:table-cell"><?= t('weather_humidity') ?></th>
                            <th class="p-4 text-center text-xs uppercase text-slate-500 hidden md:table-cell"><?= t('weather_wind') ?></th>
                            <th class="p-4 text-left text-xs uppercase text-slate-500"><?= t('archive_description') ?></th>
                            <th class="p-4 text-center text-xs uppercase text-slate-500"><?= t('archive_date') ?></th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                        <?php foreach ($history as $record): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="p-4 font-bold text-slate-900"><?= getCityDisplayName($record['city_name'], $currentLang) ?></td>
                                <td class="p-4 text-center text-lg font-black"><?= round($record['temperature'] ?? 0) ?>°C</td>
                                <td class="p-4 text-center hidden md:table-cell font-bold text-slate-700"><?= $record['humidity'] ?>%</td>
                                <td class="p-4 text-center hidden md:table-cell font-bold text-slate-700"><?= $record['wind_speed'] ?> m/s</td>
                                <td class="p-4 text-xs italic text-slate-600"><?= htmlspecialchars($record['description']) ?></td>
                                <td class="p-4 text-center text-xs text-slate-500"><?= date('Y-m-d H:i', strtotime($record['dt'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-3xl p-16 text-center border shadow-sm">
                <i class="fa-solid fa-search text-slate-200 text-7xl mb-4"></i>
                <h2 class="text-xl font-bold text-slate-400">Nincs találat a keresési feltételeknek megfelelően.</h2>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/mobile_nav.php'; ?>
</body>
</html>