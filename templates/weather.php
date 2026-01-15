<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Időjárás - <?= htmlspecialchars($viewData['city']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex min-h-screen">

<aside class="w-64 bg-white border-r border-slate-200 flex flex-col hidden md:flex shrink-0">
    <div class="p-8 text-blue-600 text-2xl font-black italic italic uppercase tracking-tighter">Weather.com</div>
    <nav class="flex-1 px-4 space-y-2 mt-4">
        <a href="/iws-2025-hu/Projekt-iws/public/" class="flex items-center gap-3 p-3 text-slate-500 hover:bg-slate-50 rounded-xl transition">
            <i class="fa-solid fa-house w-5"></i> Home
        </a>
        <a href="/iws-2025-hu/Projekt-iws/public/favorites" class="flex items-center gap-3 p-3 text-slate-500 hover:bg-slate-50 rounded-xl transition">
            <i class="fa-solid fa-star w-5 text-yellow-500"></i> Kedvencek
        </a>
        <div class="p-3 bg-blue-600 text-white rounded-xl font-medium flex items-center gap-3 shadow-md">
            <i class="fa-solid fa-cloud-sun w-5"></i> Today
        </div>
    </nav>
</aside>

<main class="flex-1 p-6 md:p-10 flex flex-col items-center">
    <div class="max-w-5xl w-full">

        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-[3rem] p-12 text-white shadow-2xl relative overflow-hidden mb-8">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center text-center md:text-left">

                <div class="flex flex-col items-center md:items-start gap-2">
                    <div class="flex items-center gap-4">
                        <h1 class="text-6xl font-black tracking-tight uppercase"><?= htmlspecialchars($viewData['city']) ?></h1>

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
                    <p class="text-blue-100 text-2xl font-medium capitalize italic">
                        <?= htmlspecialchars($viewData['data']['weather'][0]['description'] ?? 'Ismeretlen') ?>
                    </p>
                </div>

                <div class="text-9xl font-black mt-8 md:mt-0 tracking-tighter">
                    <?= round($viewData['data']['main']['temp']) ?>°
                </div>
            </div>

            <i class="fa-solid fa-cloud-sun absolute -bottom-10 -right-10 text-[20rem] text-white/10"></i>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-center">
                <h3 class="text-xl font-bold text-slate-900 border-b border-slate-50 pb-4 mb-6 uppercase tracking-wider">Részletek</h3>
                <div class="space-y-6">
                    <div class="flex justify-between items-center px-2">
                            <span class="text-slate-500 font-medium flex items-center gap-3 italic">
                                <i class="fa-solid fa-droplet text-blue-500"></i> Páratartalom
                            </span>
                        <span class="text-xl font-black text-slate-800"><?= $viewData['data']['main']['humidity'] ?>%</span>
                    </div>
                    <div class="flex justify-between items-center px-2">
                            <span class="text-slate-500 font-medium flex items-center gap-3 italic">
                                <i class="fa-solid fa-wind text-cyan-500"></i> Szélsebesség
                            </span>
                        <span class="text-xl font-black text-slate-800"><?= $viewData['data']['wind']['speed'] ?> m/s</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3 uppercase tracking-wider">
                    <i class="fa-solid fa-shirt text-orange-500"></i> Mit vegyek fel?
                </h3>
                <div class="bg-orange-50/50 p-8 rounded-3xl border border-orange-100 min-h-[120px] flex items-center">
                    <p class="text-orange-900 text-xl font-medium leading-relaxed italic">
                        "<?= htmlspecialchars($viewData['recommendation'] ?? 'Nincs ajánlás') ?>"
                    </p>
                </div>
                <div class="mt-8">
                    <a href="/iws-2025-hu/Projekt-iws/public/" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:gap-4 transition-all uppercase text-sm tracking-widest">
                        <i class="fa-solid fa-arrow-left"></i> Vissza a kereséshez
                    </a>
                </div>
            </div>

        </div>
    </div>
</main>

</body>
</html>