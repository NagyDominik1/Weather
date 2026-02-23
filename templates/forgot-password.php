<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Elfelejtett jelszó - WeatherBase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 flex h-screen items-center justify-center">
<div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-10 border border-slate-100">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-black text-slate-800">Jelszó visszaállítása</h1>
        <p class="text-slate-500 text-sm">Küldünk egy linket, amivel új jelszót állíthatsz be.</p>
    </div>

    <form id="forgotForm" action="/iws-2025-hu/Projekt-iws/public/forgot-password" method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">E-mail címed</label>
            <input id="forgot-email" type="email" name="email" required
                   class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl outline-none focus:border-blue-500 transition">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl hover:bg-blue-700 transition">
            Visszaállító link küldése
        </button>
    </form>
    <div class="mt-6 text-center text-sm">
        <a href="/iws-2025-hu/Projekt-iws/public/login" class="text-blue-600 font-bold">Vissza a belépéshez</a>
    </div>
</div>

<script src="/iws-2025-hu/Projekt-iws/public/js/validation.js"></script>
</body>
</html>