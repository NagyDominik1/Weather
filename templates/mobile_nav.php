<?php
$basePath = rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
?>
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
    <div class="grid grid-cols-5 w-full items-center py-3">

        <a href="<?php echo $basePath; ?>/" class="flex flex-col items-center gap-1 text-slate-500 hover:text-blue-600 transition">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="text-[10px] font-black uppercase tracking-tighter">Főoldal</span>
        </a>

        <a href="<?php echo $basePath; ?>/favorites" class="flex flex-col items-center gap-1 text-slate-500 hover:text-blue-600 transition">
            <i class="fa-solid fa-star text-lg"></i>
            <span class="text-[10px] font-black uppercase tracking-tighter">Kedvencek</span>
        </a>

        <a href="<?php echo $basePath; ?>/profile" class="flex flex-col items-center gap-1 text-slate-500 hover:text-blue-600 transition">
            <i class="fa-solid fa-user text-lg"></i>
            <span class="text-[10px] font-black uppercase tracking-tighter">Profil</span>
        </a>

        <a href="<?php echo $basePath; ?>/settings" class="flex flex-col items-center gap-1 text-slate-500 hover:text-blue-600 transition">
            <i class="fa-solid fa-gear text-lg"></i>
            <span class="text-[10px] font-black uppercase tracking-tighter">Beáll.</span>
        </a>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="<?php echo $basePath; ?>/login" class="flex flex-col items-center gap-1 text-blue-600 transition">
                <i class="fa-solid fa-right-to-bracket text-lg"></i>
                <span class="text-[10px] font-black uppercase tracking-tighter">Belépés</span>
            </a>
        <?php else: ?>
            <a href="<?php echo $basePath; ?>/logout" class="flex flex-col items-center gap-1 text-red-500 hover:text-red-600 transition">
                <i class="fa-solid fa-right-from-bracket text-lg"></i>
                <span class="text-[10px] font-black uppercase tracking-tighter">Kilépés</span>
            </a>
        <?php endif; ?>

    </div>
</div>

<div class="h-20 md:hidden"></div>