<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés - WeatherBase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900 flex h-screen overflow-hidden">

<main class="flex-1 overflow-y-auto p-8 flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/60 p-10 border border-slate-100">
        <div class="text-center mb-8">
            <div class="flex justify-center items-center gap-2 mb-4">
                <div class="bg-blue-600 p-2 rounded-lg">
                    <i class="fa-solid fa-bolt-lightning text-white"></i>
                </div>
                <span class="text-2xl font-black tracking-tight text-slate-800">
                    Weather<span class="text-blue-600">Base</span>
                </span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 mb-2">Bejelentkezés</h1>

            <?php if (isset($_GET['activated'])): ?>
                <div class="bg-green-50 text-green-600 p-3 rounded-xl mb-4 text-sm font-bold border border-green-100">
                    <i class="fa-solid fa-circle-check me-2"></i> Fiók sikeresen aktiválva!
                </div>
            <?php elseif (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
                <div class="bg-green-50 text-green-600 p-3 rounded-xl mb-4 text-sm font-bold border border-green-100">
                    <i class="fa-solid fa-circle-check me-2"></i> Jelszó sikeresen megváltoztatva!
                </div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-xl mb-4 text-sm font-bold border border-red-100">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> Hibás adatok vagy inaktív fiók!
                </div>
            <?php else: ?>
                <p class="text-slate-500 text-sm">Üdvözlünk újra! Jelentkezz be a fiókodba.</p>
            <?php endif; ?>
        </div>

        <form id="loginForm" action="/iws-2025-hu/Projekt-iws/public/login" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">E-mail cím</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="login-email" type="email" name="email" required
                           class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                           placeholder="pelda@email.com">
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-bold text-slate-700">Jelszó</label>
                    <a href="/iws-2025-hu/Projekt-iws/public/forgot-password" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                        Elfelejtette a jelszavát?
                    </a>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input id="login-password" type="password" name="password" required
                           class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition"
                           placeholder="********">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition active:scale-[0.98]">
                Bejelentkezés
            </button>
        </form>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-slate-500 text-sm">Még nincs fiókod?
                <a href="/iws-2025-hu/Projekt-iws/public/register" class="text-blue-600 font-bold hover:underline">Regisztrálj itt!</a>
            </p>
        </div>
    </div>
</main>

<script src="/iws-2025-hu/Projekt-iws/public/js/validation.js"></script>
</body>
</html>