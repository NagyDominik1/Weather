<?php
include __DIR__ . '/lang.php';
// Dinamikus bázis útvonal meghatározása
$basePath = rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= t('nav_favorites') ?> - <?= t('app_name') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 flex min-h-screen text-slate-800">

<?php include __DIR__ . '/sidebar_smart.php'; ?>

<main class="flex-1 p-4 md:p-10 pb-32 md:pb-10 w-full overflow-x-hidden">
    <header class="mb-8 md:mb-10">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 flex items-center gap-3">
            <i class="fa-solid fa-star text-yellow-500"></i> <?= t('favorites_title') ?>
        </h1>
        <p class="text-slate-500 italic text-sm md:text-base"><?= t('favorites_subtitle') ?></p>
    </header>

    <?php if (empty($favorites)): ?>
        <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] p-8 md:p-16 text-center border border-slate-100 shadow-sm">
            <i class="fa-solid fa-star text-slate-100 text-6xl md:text-8xl mb-6"></i>
            <h2 class="text-xl md:text-2xl font-bold text-slate-400"><?= t('favorites_empty_title') ?></h2>
            <a href="<?= $basePath ?>/" class="mt-6 inline-block text-blue-600 font-bold hover:underline">
                <?= t('favorites_empty_text') ?>
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($favorites as $fav): ?>
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all group relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-blue-50 text-blue-600 p-3 rounded-2xl">
                            <i class="fa-solid fa-city text-xl"></i>
                        </div>
                        <form action="<?= $basePath ?>/favorite/remove" method="post" onsubmit="return confirm('Biztosan törlöd?')">
                            <input type="hidden" name="city_id" value="<?= $fav['id'] ?>">
                            <button type="submit" class="text-slate-300 hover:text-red-500 transition p-2">
                                <i class="fa-solid fa-trash-can text-lg"></i>
                            </button>
                        </form>
                    </div>

                    <h3 class="text-xl md:text-2xl font-black text-slate-800 mb-6"><?= htmlspecialchars($fav['city_name']) ?></h3>

                    <a href="<?= $basePath ?>/weather?city_name=<?= urlencode($fav['city_name']) ?>"
                       class="block w-full text-center bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-blue-600 transition shadow-lg shadow-slate-100 uppercase tracking-wider text-sm">
                        <?= t('favorites_view_weather') ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/mobile_nav.php'; ?>

<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('<?= $basePath ?>/js/service-worker.js');
        });
    }
</script>
</body>
</html>