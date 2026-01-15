<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció - WEATHER.IO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900 flex h-screen overflow-hidden">



<main class="flex-1 overflow-y-auto p-8 flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/60 p-10 border border-slate-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-slate-800 mb-2">Regisztráció</h1>
            <p class="text-slate-500">Hozz létre egy fiókot az archívum eléréséhez!</p>
        </div>

        <form action="/iws-2025-hu/Projekt-iws/public/register" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">E-mail cím</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="email" name="email" required
                           class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                           placeholder="pelda@email.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Jelszó</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="password" name="password" required minlength="8"
                           class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                           placeholder="********">
                </div>
                <p class="mt-2 text-xs text-slate-400">Minimum 8 karakter hosszú legyen.</p>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition active:scale-[0.98]">
                Fiók létrehozása
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-slate-500">Már van fiókod?
                <a href="/iws-2025-hu/Projekt-iws/public/login" class="text-blue-600 font-bold hover:underline">Jelentkezz be!</a>
            </p>
        </div>
    </div>
</main>
</body>
</html>