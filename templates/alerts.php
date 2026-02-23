<?php include __DIR__ . '/lang.php'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= t('nav_alerts') ?> - <?= t('app_name') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 flex min-h-screen text-slate-800">

<div class="hidden md:flex">
    <?php include __DIR__ . '/sidebar_smart.php'; ?>
</div>

<main class="flex-1 w-full p-4 md:p-10 pb-32 md:pb-10 overflow-x-hidden">
    <div class="max-w-6xl mx-auto">

        <header class="mb-8 md:mb-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 flex flex-col md:flex-row items-center justify-center md:justify-start gap-3">
                <i class="fa-solid fa-bell text-red-500"></i> <?= t('alerts_title') ?>
            </h1>
            <p class="text-slate-500 italic text-sm md:text-base"><?= t('alerts_subtitle') ?></p>
        </header>

        <div class="bg-white rounded-[2rem] p-4 md:p-6 mb-8 border border-slate-100 shadow-sm overflow-x-auto">
            <div class="flex flex-nowrap md:flex-wrap gap-3 min-w-max md:min-w-0">
                <button class="px-4 py-2 bg-red-100 text-red-700 rounded-full font-bold text-xs md:text-sm hover:bg-red-200 transition">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= t('alerts_danger') ?>
                </button>
                <button class="px-4 py-2 bg-orange-100 text-orange-700 rounded-full font-bold text-xs md:text-sm hover:bg-orange-200 transition">
                    <i class="fa-solid fa-exclamation"></i> <?= t('alerts_warning') ?>
                </button>
                <button class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full font-bold text-xs md:text-sm hover:bg-blue-200 transition">
                    <i class="fa-solid fa-info-circle"></i> <?= t('alerts_info') ?>
                </button>
                <button class="px-4 py-2 bg-slate-100 text-slate-700 rounded-full font-bold text-xs md:text-sm hover:bg-slate-200 transition">
                    <i class="fa-solid fa-check"></i> <?= t('alerts_all') ?>
                </button>
            </div>
        </div>

        <div class="space-y-4 md:space-y-6">

            <?php if (!empty($alerts) && isset($alerts['danger']) && !empty($alerts['danger'])): ?>
                <?php foreach ($alerts['danger'] as $alert): ?>
                    <div class="bg-white rounded-[2rem] p-6 md:p-8 border-l-8 border-red-500 shadow-sm hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row items-start gap-4 md:gap-6">
                            <div class="bg-red-100 p-4 rounded-2xl shrink-0 mx-auto sm:mx-0">
                                <i class="fa-solid fa-triangle-exclamation text-3xl text-red-600"></i>
                            </div>
                            <div class="flex-1 text-center sm:text-left w-full">
                                <div class="flex flex-col sm:flex-row items-center justify-between mb-2 gap-2">
                                    <h3 class="text-xl md:text-2xl font-black text-slate-900"><?= htmlspecialchars($alert['alert_type'] ?? 'Vészjelzés') ?></h3>
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase"><?= t('alerts_danger') ?></span>
                                </div>
                                <p class="text-slate-600 mb-4 text-sm md:text-base leading-relaxed"><?= htmlspecialchars($alert['message'] ?? 'Időjárási vészhelyzet') ?></p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    <span><i class="fa-solid fa-location-dot mr-1 text-red-500"></i><?= htmlspecialchars($alert['city_name'] ?? 'Ismeretlen') ?></span>
                                    <span><i class="fa-solid fa-clock mr-1"></i><?= date('m-d H:i', strtotime($alert['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($alerts) && isset($alerts['warning']) && !empty($alerts['warning'])): ?>
                <?php foreach ($alerts['warning'] as $alert): ?>
                    <div class="bg-white rounded-[2rem] p-6 md:p-8 border-l-8 border-orange-500 shadow-sm hover:shadow-md transition">
                        <div class="flex flex-col sm:flex-row items-start gap-4 md:gap-6">
                            <div class="bg-orange-100 p-4 rounded-2xl shrink-0 mx-auto sm:mx-0">
                                <i class="fa-solid fa-cloud-showers-heavy text-3xl text-orange-600"></i>
                            </div>
                            <div class="flex-1 text-center sm:text-left w-full">
                                <div class="flex flex-col sm:flex-row items-center justify-between mb-2 gap-2">
                                    <h3 class="text-xl md:text-2xl font-black text-slate-900"><?= htmlspecialchars($alert['alert_type'] ?? 'Figyelmeztetés') ?></h3>
                                    <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-[10px] font-black uppercase"><?= t('alerts_warning') ?></span>
                                </div>
                                <p class="text-slate-600 mb-4 text-sm md:text-base leading-relaxed"><?= htmlspecialchars($alert['message'] ?? 'Időjárási figyelmeztetés') ?></p>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                    <span><i class="fa-solid fa-location-dot mr-1 text-orange-500"></i><?= htmlspecialchars($alert['city_name'] ?? 'Ismeretlen') ?></span>
                                    <span><i class="fa-solid fa-clock mr-1"></i><?= date('m-d H:i', strtotime($alert['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (empty($alerts) || (empty($alerts['danger']) && empty($alerts['warning']) && empty($alerts['info']))): ?>
                <div class="bg-white rounded-[2.5rem] p-12 md:p-20 text-center border border-slate-100 shadow-sm">
                    <i class="fa-solid fa-circle-check text-green-500 text-7xl md:text-8xl mb-6"></i>
                    <h2 class="text-2xl font-black text-slate-400 uppercase tracking-tighter"><?= t('alerts_empty') ?></h2>
                    <p class="text-slate-500 mt-2 text-sm md:text-base"><?= t('alerts_empty_text') ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-12 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-900 rounded-[2.5rem] p-6 md:p-10 border border-blue-100 dark:border-slate-700 shadow-sm">
            <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
                <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                    <i class="fa-solid fa-bell-concierge text-blue-600"></i> <?= t('alerts_settings_title') ?>
                </h3>
                <a href="/iws-2025-hu/Projekt-iws/public/settings" class="w-full md:w-auto text-center bg-blue-600 text-white px-8 py-3 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-100 dark:shadow-none">
                    <i class="fa-solid fa-gear mr-2"></i> <?= t('settings_title') ?>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php
                $userId = $_SESSION['user_id'] ?? null;
                $notifSettings = ['notify_email' => 0, 'notify_push' => 0, 'notify_alerts' => 0, 'notify_daily' => 0];
                if ($userId) {
                    $db = Database::getConnection();
                    $stmt = $db->prepare("SELECT notify_email, notify_push, notify_alerts, notify_daily FROM users WHERE id = ?");
                    $stmt->execute([$userId]);
                    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($settings) $notifSettings = $settings;
                }

                $items = [
                        ['icon' => 'fa-envelope', 'color' => 'blue', 'label' => t('alerts_email'), 'val' => $notifSettings['notify_email']],
                        ['icon' => 'fa-mobile-screen-button', 'color' => 'purple', 'label' => t('alerts_push'), 'val' => $notifSettings['notify_push']],
                        ['icon' => 'fa-triangle-exclamation', 'color' => 'orange', 'label' => t('alerts_emergency'), 'val' => $notifSettings['notify_alerts']],
                        ['icon' => 'fa-newspaper', 'color' => 'green', 'label' => t('alerts_daily'), 'val' => $notifSettings['notify_daily']],
                ];

                foreach ($items as $item): ?>
                    <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-50 dark:border-slate-700 <?= $item['val'] ? 'ring-2 ring-green-100 dark:ring-green-900/30' : 'opacity-60' ?>">
                        <div class="flex items-center gap-3 text-sm font-bold text-slate-700 dark:text-slate-200">
                            <div class="bg-<?= $item['color'] ?>-50 dark:bg-slate-700 p-2.5 rounded-xl text-<?= $item['color'] ?>-600">
                                <i class="fa-solid <?= $item['icon'] ?>"></i>
                            </div>
                            <?= $item['label'] ?>
                        </div>
                        <span class="text-[10px] font-black <?= $item['val'] ? 'text-green-600' : 'text-slate-400' ?>">
                        <?= $item['val'] ? '✅ BE' : '❌ KI' ?>
                    </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
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