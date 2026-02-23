<style>
    /* ========================================== */
    /* GLOBÁLIS DARK MODE - TELJES LEFEDÉS       */
    /* ========================================== */

    .dark {
        --tw-bg-opacity: 1;
        background-color: rgb(15 23 42 / var(--tw-bg-opacity));
    }

    .dark body {
        background-color: #0f172a;
        color: #e2e8f0;
    }

    /* ========================================== */
    /* KÁRTYÁK ÉS BOXOK                          */
    /* ========================================== */

    .dark .bg-white {
        background-color: #1e293b !important;
    }

    /* ========================================== */
    /* SZÖVEG SZÍNEK - MINDEN ÁRNYALAT           */
    /* ========================================== */

    /* Fekete / Sötét szövegek → Fehér */
    .dark .text-slate-900,
    .dark h1,
    .dark h2,
    .dark h3,
    .dark h4,
    .dark h5,
    .dark h6,
    .dark .font-bold.text-slate-900,
    .dark .font-black.text-slate-900,
    .dark .font-black.text-slate-800 {
        color: #f1f5f9 !important;
    }

    .dark .text-slate-800 {
        color: #f1f5f9 !important;
    }

    .dark .text-slate-700 {
        color: #cbd5e1 !important;
    }

    .dark .text-slate-600 {
        color: #94a3b8 !important;
    }

    .dark .text-slate-500 {
        color: #64748b !important;
    }

    .dark .text-slate-400 {
        color: #475569 !important;
    }

    /* ========================================== */
    /* HÁTTÉR SZÍNEK                             */
    /* ========================================== */

    .dark .bg-slate-50 {
        background-color: #0f172a !important;
    }

    .dark .bg-slate-100 {
        background-color: #1e293b !important;
    }

    .dark .bg-slate-200 {
        background-color: #334155 !important;
    }

    /* Színes háttér 50-es árnyalatok */
    .dark .bg-blue-50 {
        background-color: #1e3a5f !important;
    }

    .dark .bg-green-50 {
        background-color: #065f46 !important;
    }

    .dark .bg-red-50 {
        background-color: #7f1d1d !important;
    }

    .dark .bg-purple-50 {
        background-color: #581c87 !important;
    }

    .dark .bg-orange-50 {
        background-color: #7c2d12 !important;
    }

    .dark .bg-yellow-50 {
        background-color: #713f12 !important;
    }

    .dark .bg-cyan-50 {
        background-color: #164e63 !important;
    }

    /* Színes háttér 100-as árnyalatok */
    .dark .bg-blue-100 {
        background-color: #1e40af !important;
        color: #dbeafe !important;
    }

    .dark .bg-green-100 {
        background-color: #065f46 !important;
        color: #d1fae5 !important;
    }

    .dark .bg-red-100 {
        background-color: #991b1b !important;
        color: #fecaca !important;
    }

    .dark .bg-purple-100 {
        background-color: #6b21a8 !important;
        color: #e9d5ff !important;
    }

    .dark .bg-orange-100 {
        background-color: #9a3412 !important;
        color: #fed7aa !important;
    }

    .dark .bg-yellow-100 {
        background-color: #854d0e !important;
        color: #fef3c7 !important;
    }

    .dark .bg-cyan-100 {
        background-color: #155e75 !important;
        color: #cffafe !important;
    }

    /* ========================================== */
    /* BORDEREK                                  */
    /* ========================================== */

    .dark .border-slate-100,
    .dark .border-slate-200 {
        border-color: #334155 !important;
    }

    .dark .border-red-200 {
        border-color: #991b1b !important;
    }

    .dark .border-blue-100 {
        border-color: #1e40af !important;
    }

    .dark .border-green-200 {
        border-color: #065f46 !important;
    }

    /* ========================================== */
    /* HOVER EFFEKTEK                            */
    /* ========================================== */

    .dark .hover\:bg-slate-50:hover {
        background-color: #334155 !important;
    }

    .dark .hover\:bg-blue-50:hover {
        background-color: #1e3a5f !important;
    }

    .dark .hover\:bg-red-200:hover {
        background-color: #991b1b !important;
    }

    .dark .hover\:bg-orange-200:hover {
        background-color: #9a3412 !important;
    }

    .dark .hover\:bg-blue-200:hover {
        background-color: #1e40af !important;
    }

    /* ========================================== */
    /* INPUT MEZŐK ÉS SELECTEK                  */
    /* ========================================== */

    .dark input[type="text"],
    .dark input[type="email"],
    .dark input[type="password"],
    .dark input[type="date"],
    .dark select,
    .dark textarea {
        background-color: #1e293b !important;
        color: #f1f5f9 !important;
        border-color: #475569 !important;
    }

    .dark input::placeholder {
        color: #64748b !important;
    }

    .dark input:disabled,
    .dark select:disabled {
        background-color: #334155 !important;
        color: #64748b !important;
    }

    .dark input:focus,
    .dark select:focus {
        border-color: #3b82f6 !important;
    }

    /* ========================================== */
    /* TÁBLÁZATOK                                */
    /* ========================================== */

    .dark table {
        color: #e2e8f0 !important;
    }

    .dark thead {
        background-color: #1e293b !important;
    }

    .dark tbody tr {
        border-color: #334155 !important;
    }

    .dark tbody tr:hover {
        background-color: #334155 !important;
    }

    .dark td,
    .dark th {
        color: #e2e8f0 !important;
    }

    .dark .bg-slate-50 thead {
        background-color: #1e293b !important;
    }

    /* ========================================== */
    /* ALERT BOXOK ÉS ÉRTESÍTÉSEK               */
    /* ========================================== */

    .dark .bg-green-100.border-l-4 {
        background-color: #065f46 !important;
        color: #d1fae5 !important;
        border-left-color: #10b981 !important;
    }

    .dark .bg-red-100.border-l-4 {
        background-color: #7f1d1d !important;
        color: #fecaca !important;
        border-left-color: #ef4444 !important;
    }

    .dark .text-green-700,
    .dark .text-green-600 {
        color: #86efac !important;
    }

    .dark .text-red-700,
    .dark .text-red-600 {
        color: #fca5a5 !important;
    }

    .dark .text-blue-700,
    .dark .text-blue-600 {
        color: #93c5fd !important;
    }

    .dark .text-orange-700,
    .dark .text-orange-600 {
        color: #fdba74 !important;
    }

    /* ========================================== */
    /* GRADIENSEK (megmaradnak színesek)        */
    /* ========================================== */

    .dark .from-blue-50,
    .dark .to-cyan-50 {
        /* Gradiensek nem változnak */
    }

    .dark .from-blue-600,
    .dark .to-cyan-600,
    .dark .from-purple-600,
    .dark .to-indigo-600 {
        /* Élénk gradiensek megmaradnak */
    }

    /* ========================================== */
    /* TOGGLE SWITCH                             */
    /* ========================================== */

    .toggle {
        position: relative;
    }

    .toggle::before {
        content: '';
        position: absolute;
        width: 24px;
        height: 24px;
        background: white;
        border-radius: 50%;
        top: 2px;
        left: 2px;
        transition: left 0.3s ease;
    }

    .toggle:checked::before {
        left: calc(100% - 26px);
    }

    /* Dark mode-ban a toggle háttere */
    .dark .toggle {
        background-color: #475569 !important;
    }

    .dark .toggle:checked {
        background-color: #3b82f6 !important;
    }

    /* ========================================== */
    /* PEER CHECKED (Radio/Checkbox)            */
    /* ========================================== */

    .dark .peer-checked\:bg-blue-50 {
        background-color: #1e3a5f !important;
    }

    .dark .peer-checked\:border-blue-600 {
        border-color: #3b82f6 !important;
    }

    /* ========================================== */
    /* KÉPEK ÉS IKONOK (színesek maradnak)      */
    /* ========================================== */

    .dark .text-blue-600,
    .dark .text-red-500,
    .dark .text-green-500,
    .dark .text-purple-600,
    .dark .text-orange-600,
    .dark .text-yellow-500,
    .dark .text-cyan-600,
    .dark .text-indigo-500 {
        /* Élénk színek megmaradnak az iconoknál */
    }

    /* ========================================== */
    /* SHADOW-K (dark mode-ban finomabbak)      */
    /* ========================================== */

    .dark .shadow-lg,
    .dark .shadow-xl,
    .dark .shadow-2xl {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3),
        0 4px 6px -2px rgba(0, 0, 0, 0.25) !important;
    }

    /* ========================================== */
    /* OPACITY ÉS ÁTLÁTSZÓSÁG                   */
    /* ========================================== */

    .dark .opacity-50 {
        opacity: 0.3 !important; /* Még sötétebb disabled elemek */
    }

    /* ========================================== */
    /* SCROLLBAR (OPCIONÁLIS)                    */
    /* ========================================== */

    .dark ::-webkit-scrollbar {
        width: 12px;
    }

    .dark ::-webkit-scrollbar-track {
        background: #1e293b;
    }

    .dark ::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 6px;
    }

    .dark ::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
</style>

<script>
    // Dark mode inicializálás (cookie-ból) - AZONNAL töltődik
    (function() {
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        const theme = getCookie('theme') || 'light';
        const html = document.documentElement;

        if (theme === 'dark') {
            html.classList.add('dark');
        } else if (theme === 'auto') {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                html.classList.add('dark');
            }
        }

        // Auto mode követése
        if (theme === 'auto') {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (e.matches) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            });
        }
    })();
</script>