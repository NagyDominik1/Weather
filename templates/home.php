<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Időjárás - Főoldal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex min-h-screen text-slate-800">

<aside class="w-72 bg-white border-r border-slate-200 flex flex-col hidden md:flex shrink-0 h-screen sticky top-0">
    <div class="p-8">
        <h2 class="text-blue-600 text-2xl font-black flex items-center gap-2">
            <i class="fa-solid fa-bolt-lightning"></i> WEATHER.IO
        </h2>
    </div>

    <nav class="flex-1 px-4 space-y-1">
        <a href="/iws-2025-hu/Projekt-iws/public/"
           class="flex items-center gap-3 p-3 bg-blue-50 text-blue-600 rounded-2xl font-bold transition">
            <i class="fa-solid fa-house"></i> Irányítópult
        </a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/iws-2025-hu/Projekt-iws/public/favorites"
               class="flex items-center gap-3 p-3 text-slate-500 hover:bg-slate-50 rounded-2xl transition group">
                <i class="fa-solid fa-star group-hover:text-yellow-500 transition"></i> Kedvenc városok
                <span class="ml-auto bg-slate-100 text-slate-500 text-xs py-1 px-2 rounded-lg">
                    <?= isset($favorites) ? count($favorites) : 0 ?>
                </span>
            </a>

            <a href="/iws-2025-hu/Projekt-iws/public/archive"
               class="flex items-center gap-3 p-3 text-slate-500 hover:bg-slate-50 rounded-2xl transition group">
                <i class="fa-solid fa-clock-rotate-left group-hover:text-blue-500 transition"></i> Archívum
            </a>

            <div class="pt-4 mt-4 border-t border-slate-100 px-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Felhasználó</p>
                <div class="flex items-center gap-2 text-slate-700 text-sm mb-3 font-medium">
                    <i class="fa-solid fa-circle-user text-blue-400 text-lg"></i>
                    <?= htmlspecialchars($_SESSION['email']) ?>
                </div>
                <a href="/iws-2025-hu/Projekt-iws/public/logout"
                   class="flex items-center gap-3 p-2 text-red-500 hover:bg-red-50 rounded-xl transition text-sm font-bold">
                    <i class="fa-solid fa-right-from-bracket"></i> Kijelentkezés
                </a>
            </div>

        <?php else: ?>
            <div class="pt-4 mt-4 border-t border-slate-100 px-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Fiók</p>
                <a href="/iws-2025-hu/Projekt-iws/public/login"
                   class="flex items-center gap-3 p-3 text-slate-500 hover:bg-slate-50 rounded-2xl transition group">
                    <i class="fa-solid fa-key group-hover:text-blue-500"></i> Bejelentkezés
                </a>
                <a href="/iws-2025-hu/Projekt-iws/public/register"
                   class="flex items-center gap-3 p-3 text-slate-500 hover:bg-slate-50 rounded-2xl transition group">
                    <i class="fa-solid fa-user-plus group-hover:text-green-500"></i> Regisztráció
                </a>
            </div>
        <?php endif; ?>
    </nav>
</aside>

<main class="flex-1 p-8">
    <div class="max-w-4xl mx-auto">

        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 mb-12">
            <h1 class="text-4xl font-black text-slate-900 mb-2">Hogy van az idő?</h1>
            <p class="text-slate-400 mb-8">Válassz ki egy várost a listából.</p>

            <form action="/iws-2025-hu/Projekt-iws/public/weather" method="get" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <input
                            type="text"
                            name="city_name"
                            placeholder="Írd be a város nevét (pl. Budapest, London...)"
                            required
                            class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl text-lg focus:ring-4 focus:ring-blue-100 outline-none transition"
                    >
                    <i class="fa-solid fa-magnifying-glass absolute right-5 top-1/2 -translate-y-1/2 text-slate-300"></i>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-10 py-4 rounded-2xl shadow-xl shadow-blue-100 transition-all active:scale-95">
                    Mehet
                </button>
            </form>
        </div>

    </div>
</main>
</body>
</html>