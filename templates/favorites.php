<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kedvenceim - Weather App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex min-h-screen text-slate-800">

<aside class="w-72 bg-white border-r border-slate-200 flex flex-col hidden md:flex shrink-0">
    <div class="p-8">
        <h2 class="text-blue-600 text-2xl font-black flex items-center gap-2">
            <i class="fa-solid fa-bolt-lightning"></i> WEATHER.IO
        </h2>
    </div>
    <nav class="flex-1 px-4 space-y-1">
        <a href="/iws-2025-hu/Projekt-iws/public/" class="flex items-center gap-3 p-3 text-slate-500 hover:bg-slate-50 rounded-2xl transition">
            <i class="fa-solid fa-house"></i> Irányítópult
        </a>

        <a href="/iws-2025-hu/Projekt-iws/public/favorites" class="flex items-center gap-3 p-3 text-slate-500 hover:bg-slate-50 rounded-2xl transition">
            <i class="fa-solid fa-star text-yellow-500"></i> Kedvenc városok
        </a>
    </nav>
</aside>

<main class="flex-1 p-10">
    <header class="mb-10">
        <h1 class="text-4xl font-black text-slate-900 mb-2">Mentett helyszíneid</h1>
        <p class="text-slate-500 italic">Itt találod az összes várost, amit kedvencnek jelöltél.</p>
    </header>

    <?php if (empty($favorites)): ?>
        <div class="bg-white rounded-[2.5rem] p-16 text-center border border-slate-100 shadow-sm">
            <i class="fa-solid fa-star text-slate-100 text-8xl mb-6"></i>
            <h2 class="text-2xl font-bold text-slate-400">Még nincs kedvenc helyszíned</h2>
            <a href="/iws-2025-hu/Projekt-iws/public/" class="mt-6 inline-block text-blue-600 font-bold hover:underline">Indíts egy keresést!</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($favorites as $fav): ?>
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all group relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-blue-50 text-blue-600 p-3 rounded-2xl">
                            <i class="fa-solid fa-city text-xl"></i>
                        </div>
                        <form action="/iws-2025-hu/Projekt-iws/public/favorite/remove" method="post">
                            <input type="hidden" name="city_id" value="<?= $fav['id'] ?>">
                            <button type="submit" class="text-slate-200 hover:text-red-500 transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>

                    <h3 class="text-2xl font-black text-slate-800 mb-6"><?= htmlspecialchars($fav['city_name']) ?></h3>

                    <a href="/iws-2025-hu/Projekt-iws/public/weather?city_name=<?= urlencode($fav['city_name']) ?>"
                       class="block w-full text-center bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-blue-600 transition shadow-lg shadow-slate-100">
                        Időjárás megtekintése
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>