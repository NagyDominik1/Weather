<?php
include __DIR__ . '/lang.php';
// Dinamikus útvonal kezelése
$basePath = rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= t('nav_profile') ?> - <?= t('app_name') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 flex min-h-screen text-slate-800">

<?php include __DIR__ . '/sidebar_smart.php'; ?>

<main class="flex-1 p-4 md:p-10 pb-32 md:pb-10 w-full overflow-x-hidden">
    <header class="mb-6 md:mb-10">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 flex items-center gap-3">
            <i class="fa-solid fa-user text-indigo-500"></i> <?= t('profile_title') ?>
        </h1>
        <p class="text-slate-500 italic text-sm md:text-base"><?= t('profile_subtitle') ?></p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-lg text-center h-fit">
                <div class="w-24 h-24 md:w-32 md:h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto mb-6 flex items-center justify-center text-white text-4xl md:text-5xl font-black shadow-xl">
                    <?= strtoupper(substr($_SESSION['email'] ?? 'U', 0, 1)) ?>
                </div>

                <h2 class="text-xl md:text-2xl font-black text-slate-900 mb-2 break-all">
                    <?= htmlspecialchars($_SESSION['email'] ?? 'user@example.com') ?>
                </h2>
                <p class="text-sm text-slate-500 mb-6"><?= t('profile_member_since') ?></p>

                <div class="grid grid-cols-2 gap-3 md:gap-4 mb-6">
                    <div class="bg-blue-50 rounded-2xl p-3 md:p-4">
                        <p class="text-2xl md:text-3xl font-black text-blue-600"><?= $favoriteCount ?? 0 ?></p>
                        <p class="text-[10px] md:text-xs text-slate-600 font-bold uppercase"><?= t('profile_favorites_count') ?></p>
                    </div>
                    <div class="bg-purple-50 rounded-2xl p-3 md:p-4">
                        <p class="text-2xl md:text-3xl font-black text-purple-600">12</p>
                        <p class="text-[10px] md:text-xs text-slate-600 font-bold uppercase"><?= t('profile_searches_count') ?></p>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <p class="text-[10px] md:text-xs text-slate-500 uppercase tracking-wider mb-1"><?= t('profile_member_since') ?></p>
                    <p class="text-base md:text-lg font-bold text-slate-900">
                        <?php
                        if (isset($user['created_at'])) {
                            echo date('Y. F d.', strtotime($user['created_at']));
                        } else {
                            echo '2026. ' . t('monday') . ' 15.';
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-lg">
                <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-envelope text-blue-600"></i> <?= t('profile_email_change') ?>
                </h3>

                <?php if (isset($_GET['success']) && $_GET['success'] === 'email_updated'): ?>
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-2xl text-sm">
                        <p class="font-bold"><i class="fa-solid fa-check-circle mr-2"></i> <?= t('settings_saved') ?></p>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-2xl text-sm">
                        <p class="font-bold"><i class="fa-solid fa-times-circle mr-2"></i> Ez az email már használatban van!</p>
                    </div>
                <?php endif; ?>

                <form id="profileEmailForm" action="<?= $basePath ?>/profile/update-email" method="post" class="space-y-4">
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-600 mb-2 uppercase"><?= t('login_email') ?></label>
                        <input type="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" disabled
                               class="w-full p-4 rounded-2xl border-2 border-slate-200 bg-slate-50 font-medium text-sm">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-600 mb-2 uppercase"><?= t('profile_new_email') ?></label>
                        <input id="profile-new-email" type="email" name="new_email" required
                               class="w-full p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-400 transition font-medium focus:outline-none focus:border-blue-600 text-sm"
                               placeholder="newemail@example.com">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-600 mb-2 uppercase"><?= t('profile_password_confirm') ?></label>
                        <input id="profile-email-password" type="password" name="password" required
                               class="w-full p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-400 transition font-medium focus:outline-none focus:border-blue-600 text-sm"
                               placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg text-sm uppercase tracking-widest">
                        <i class="fa-solid fa-check mr-2"></i> <?= t('profile_change_email_btn') ?>
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100 shadow-lg">
                <h3 class="text-xl md:text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-lock text-purple-600"></i> <?= t('profile_password_change') ?>
                </h3>

                <?php if (isset($_GET['success']) && $_GET['success'] === 'password_changed'): ?>
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-2xl text-sm">
                        <p class="font-bold"><i class="fa-solid fa-check-circle mr-2"></i> <?= t('settings_saved') ?></p>
                    </div>
                <?php endif; ?>

                <form id="profilePasswordForm" action="<?= $basePath ?>/profile/change-password" method="post" class="space-y-4">
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-600 mb-2 uppercase"><?= t('profile_current_password') ?></label>
                        <input id="profile-current-password" type="password" name="current_password" required
                               class="w-full p-4 rounded-2xl border-2 border-slate-200 hover:border-purple-400 transition font-medium focus:outline-none focus:border-purple-600 text-sm"
                               placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-600 mb-2 uppercase"><?= t('profile_new_password') ?></label>
                        <input id="profile-new-password" type="password" name="new_password" required
                               class="w-full p-4 rounded-2xl border-2 border-slate-200 hover:border-purple-400 transition font-medium focus:outline-none focus:border-purple-600 text-sm"
                               placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-600 mb-2 uppercase"><?= t('profile_confirm_password') ?></label>
                        <input id="profile-confirm-password" type="password" name="confirm_password" required
                               class="w-full p-4 rounded-2xl border-2 border-slate-200 hover:border-purple-400 transition font-medium focus:outline-none focus:border-purple-600 text-sm"
                               placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full bg-purple-600 text-white py-4 rounded-2xl font-bold hover:bg-purple-700 transition shadow-lg text-sm uppercase tracking-widest">
                        <i class="fa-solid fa-key mr-2"></i> <?= t('profile_change_password_btn') ?>
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-3xl p-6 md:p-8 border border-red-200 shadow-lg">
                <h3 class="text-xl md:text-2xl font-black text-red-600 mb-4 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= t('profile_danger_zone') ?>
                </h3>
                <p class="text-slate-600 mb-6 text-sm">
                    <?= t('profile_delete_warning') ?>
                </p>
                <button onclick="return confirm('<?= t('profile_delete_warning') ?>')"
                        class="w-full bg-red-600 text-white py-4 rounded-2xl font-bold hover:bg-red-700 transition shadow-lg text-sm uppercase tracking-widest">
                    <i class="fa-solid fa-trash-can mr-2"></i> <?= t('profile_delete_account') ?>
                </button>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/mobile_nav.php'; ?>

<script src="<?= $basePath ?>/js/validation.js"></script>
</body>
</html>