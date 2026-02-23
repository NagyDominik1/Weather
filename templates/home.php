<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/lang.php';

// Dinamikus bázis útvonal meghatározása
$basePath = rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');

// Biztonsági ellenőrzés a kedvencekhez
if (!isset($favorites)) { $favorites = []; }
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('app_name'); ?> - <?php echo t('nav_home'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="manifest" href="<?php echo $basePath; ?>/manifest.json">
    <meta name="theme-color" content="#3b82f6">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#0f172a">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="WeatherBase">

    <link rel="apple-touch-icon" href="<?php echo $basePath; ?>/icons/apple-touch-icon.png">
    <meta name="msapplication-TileImage" content="<?php echo $basePath; ?>/icons/web-app-manifest-192x192.png">
    <meta name="msapplication-TileColor" content="#3b82f6">
    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 flex min-h-screen">

<?php include __DIR__ . '/sidebar_smart.php'; ?>

<main class="flex-1 p-6 md:p-10 pb-32 md:pb-10">
    <div class="max-w-4xl mx-auto">

        <header class="mb-12 text-center">
            <h1 class="text-5xl md:text-6xl font-black text-slate-900 mb-4">
                <?php echo t('home_title'); ?> ☀️
            </h1>
            <p class="text-xl text-slate-600 italic">
                <?php echo t('home_subtitle'); ?>
            </p>
        </header>

        <div class="bg-white rounded-[3rem] p-8 md:p-12 shadow-2xl border border-slate-100 mb-12">
            <form action="<?php echo $basePath; ?>/weather" method="get" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-3 uppercase tracking-wider">
                        <i class="fa-solid fa-magnifying-glass mr-2 text-blue-600"></i> <?php echo t('home_search_label'); ?>
                    </label>
                    <input
                            type="text"
                            name="city_name"
                            required
                            placeholder="<?php echo t('home_search_placeholder'); ?>"
                            class="w-full p-6 text-xl rounded-2xl border-2 border-slate-200 hover:border-blue-400 focus:border-blue-600 transition outline-none font-medium"
                    >
                </div>
                <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-6 rounded-2xl font-black text-lg hover:shadow-2xl hover:scale-[1.02] transition-all duration-300"
                >
                    <i class="fa-solid fa-cloud-sun mr-3"></i> <?php echo t('home_search_btn'); ?>
                </button>
            </form>
        </div>

        <?php if (isset($_SESSION['user_id']) && !empty($favorites)): ?>
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-lg border border-slate-100 dark:border-slate-700 mb-12">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-star text-yellow-500"></i> <?php echo t('home_favorites_title'); ?>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php foreach (array_slice($favorites, 0, 6) as $fav): ?>
                        <a href="<?php echo $basePath; ?>/weather?city_name=<?php echo urlencode($fav['city_name']); ?>"
                           class="p-5 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900 dark:to-cyan-900 rounded-2xl border-2 border-blue-100 dark:border-blue-500 hover:border-blue-400 dark:hover:border-blue-400 transition group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-city text-2xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform"></i>
                                    <span class="font-bold text-slate-900 dark:text-white text-lg"><?php echo htmlspecialchars($fav['city_name']); ?></span>
                                </div>
                                <i class="fa-solid fa-arrow-right text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="<?php echo $basePath; ?>/archive" class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="bg-purple-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-clock-rotate-left text-3xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2"><?php echo t('nav_archive'); ?></h3>
                <p class="text-sm text-slate-600"><?php echo t('archive_subtitle'); ?></p>
            </a>

            <a href="<?php echo $basePath; ?>/alerts" class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="bg-red-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bell text-3xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2"><?php echo t('nav_alerts'); ?></h3>
                <p class="text-sm text-slate-600">Aktuális riasztások és figyelmeztetések.</p>
            </a>

            <a href="<?php echo $basePath; ?>/compare" class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div class="bg-green-100 w-16 h-16 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-code-compare text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2"><?php echo t('nav_compare'); ?></h3>
                <p class="text-sm text-slate-600">Városok időjárásának összehasonlítása.</p>
            </a>
        </div>

    </div>
</main>

<?php include __DIR__ . '/mobile_nav.php'; ?>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('<?php echo $basePath; ?>/js/service-worker.js')
                .then((reg) => console.log('✅ Service Worker OK'))
                .catch((err) => console.warn('❌ SW Hiba:', err));
        });
    }
</script>
</body>
</html>