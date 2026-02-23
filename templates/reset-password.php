<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Új jelszó beállítása - WeatherBase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php include __DIR__ . '/dark-mode-styles.php'; ?>
</head>
<body class="bg-slate-50 flex h-screen items-center justify-center">
<div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-10 border border-slate-100">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-black text-slate-800">Új jelszó megadása</h1>
        <p class="text-slate-500 text-sm">Kérjük, adj meg egy erős, új jelszót!</p>
    </div>

    <form id="resetForm" action="/iws-2025-hu/Projekt-iws/public/update-password" method="POST" class="space-y-6">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Új jelszó</label>
            <input id="reset-password" type="password" name="password" required minlength="6"
                   class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-blue-500 outline-none transition">
            <p class="mt-2 text-xs text-slate-400">Minimum 6 karakter hosszú legyen.</p>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Jelszó megerősítése</label>
            <input id="reset-confirm" type="password" name="confirm_password" required minlength="6"
                   class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-2xl focus:border-blue-500 outline-none transition">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl hover:bg-blue-700 transition">
            Jelszó mentése
        </button>
    </form>
</div>

<script src="/iws-2025-hu/Projekt-iws/public/js/validation.js"></script>
</body>
</html>