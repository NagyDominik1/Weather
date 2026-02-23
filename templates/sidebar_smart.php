<?php
// 1. Dinamikus bázis útvonal meghatározása
$basePath = rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');

// 2. Aktuális oldal meghatározása a kijelöléshez
$requestUri = $_SERVER['REQUEST_URI'];
$currentPage = 'home';

if (strpos($requestUri, 'favorites') !== false) { $currentPage = 'favorites'; }
elseif (strpos($requestUri, 'archive') !== false) { $currentPage = 'archive'; }
elseif (strpos($requestUri, 'alerts') !== false) { $currentPage = 'alerts'; }
elseif (strpos($requestUri, 'compare') !== false) { $currentPage = 'compare'; }
elseif (strpos($requestUri, 'profile') !== false) { $currentPage = 'profile'; }
elseif (strpos($requestUri, 'settings') !== false) { $currentPage = 'settings'; }

include_once __DIR__ . '/lang.php';
?>

<aside class="w-72 bg-white border-r border-slate-200 flex flex-col hidden md:flex shrink-0">
    <div class="p-8">
        <h2 class="text-blue-600 text-2xl font-black flex items-center gap-2 italic uppercase tracking-tighter">
            <i class="fa-solid fa-cloud-bolt"></i> <?php echo t('app_name'); ?>
        </h2>
    </div>

    <nav class="flex-1 px-4 space-y-1">
        <a href="<?php echo $basePath; ?>/"
           class="flex items-center gap-3 p-3 rounded-2xl transition group <?php echo ($currentPage === 'home') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'; ?>">
            <i class="fa-solid fa-house <?php echo ($currentPage === 'home') ? 'text-white' : 'text-slate-400 group-hover:text-blue-600'; ?> transition"></i>
            <span class="font-medium"><?php echo t('nav_home'); ?></span>
        </a>

        <a href="<?php echo $basePath; ?>/favorites"
           class="flex items-center gap-3 p-3 rounded-2xl transition group <?php echo ($currentPage === 'favorites') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'; ?>">
            <i class="fa-solid fa-star <?php echo ($currentPage === 'favorites') ? 'text-yellow-300' : 'text-yellow-500'; ?> group-hover:scale-110 transition-transform"></i>
            <span class="font-medium"><?php echo t('nav_favorites'); ?></span>
        </a>

        <a href="<?php echo $basePath; ?>/archive"
           class="flex items-center gap-3 p-3 rounded-2xl transition group <?php echo ($currentPage === 'archive') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'; ?>">
            <i class="fa-solid fa-clock-rotate-left <?php echo ($currentPage === 'archive') ? 'text-purple-300' : 'text-purple-500'; ?> group-hover:scale-110 transition-transform"></i>
            <span class="font-medium"><?php echo t('nav_archive'); ?></span>
        </a>

        <a href="<?php echo $basePath; ?>/alerts"
           class="flex items-center gap-3 p-3 rounded-2xl transition group relative <?php echo ($currentPage === 'alerts') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'; ?>">
            <i class="fa-solid fa-bell <?php echo ($currentPage === 'alerts') ? 'text-red-300' : 'text-red-500'; ?> group-hover:scale-110 transition-transform"></i>
            <span class="font-medium"><?php echo t('nav_alerts'); ?></span>
        </a>

        <a href="<?php echo $basePath; ?>/compare"
           class="flex items-center gap-3 p-3 rounded-2xl transition group <?php echo ($currentPage === 'compare') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'; ?>">
            <i class="fa-solid fa-code-compare <?php echo ($currentPage === 'compare') ? 'text-green-300' : 'text-green-500'; ?> group-hover:scale-110 transition-transform"></i>
            <span class="font-medium"><?php echo t('nav_compare'); ?></span>
        </a>

        <a href="<?php echo $basePath; ?>/profile"
           class="flex items-center gap-3 p-3 rounded-2xl transition group <?php echo ($currentPage === 'profile') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'; ?>">
            <i class="fa-solid fa-user <?php echo ($currentPage === 'profile') ? 'text-indigo-300' : 'text-indigo-500'; ?> group-hover:scale-110 transition-transform"></i>
            <span class="font-medium"><?php echo t('nav_profile'); ?></span>
        </a>

        <a href="<?php echo $basePath; ?>/settings"
           class="flex items-center gap-3 p-3 rounded-2xl transition group <?php echo ($currentPage === 'settings') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'; ?>">
            <i class="fa-solid fa-gear <?php echo ($currentPage === 'settings') ? 'text-slate-300' : 'text-slate-400'; ?> group-hover:rotate-90 transition-transform duration-300"></i>
            <span class="font-medium"><?php echo t('nav_settings'); ?></span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-100">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?php echo $basePath; ?>/logout"
               class="flex items-center gap-3 p-3 text-red-500 hover:bg-red-50 rounded-2xl transition font-medium">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span><?php echo t('nav_logout'); ?></span>
            </a>
        <?php else: ?>
            <a href="<?php echo $basePath; ?>/login"
               class="flex items-center gap-3 p-3 text-blue-600 hover:bg-blue-50 rounded-2xl transition font-medium border border-blue-100">
                <i class="fa-solid fa-right-to-bracket"></i>
                <span><?php echo t('nav_login'); ?></span>
            </a>
        <?php endif; ?>
    </div>
</aside>