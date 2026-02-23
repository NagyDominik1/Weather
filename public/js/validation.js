/**
 * WEATHERBASE - KLIENS OLDALI FORM VALIDÁCIÓ
 * Használat: <script src="/js/validation.js"></script>
 * Minden form oldalon includolni kell!
 */

// ============================================================
// SEGÉDFÜGGVÉNYEK
// ============================================================

function showError(inputEl, message) {
    inputEl.classList.add('border-red-500');
    inputEl.classList.remove('border-slate-200', 'border-green-500');

    let errorEl = inputEl.parentElement.querySelector('.error-msg');
    if (!errorEl) {
        errorEl = document.createElement('p');
        errorEl.className = 'error-msg text-red-500 text-sm mt-1 font-medium';
        inputEl.parentElement.appendChild(errorEl);
    }
    errorEl.textContent = message;
}

function showSuccess(inputEl) {
    inputEl.classList.remove('border-red-500');
    inputEl.classList.add('border-green-500', 'border-slate-200');

    const errorEl = inputEl.parentElement.querySelector('.error-msg');
    if (errorEl) errorEl.remove();
}

function clearError(inputEl) {
    inputEl.classList.remove('border-red-500', 'border-green-500');
    inputEl.classList.add('border-slate-200');
    const errorEl = inputEl.parentElement.querySelector('.error-msg');
    if (errorEl) errorEl.remove();
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isValidPassword(password) {
    return password.length >= 6;
}

// ============================================================
// REGISTER VALIDÁCIÓ
// ============================================================

const registerForm = document.getElementById('registerForm');
if (registerForm) {
    const emailInput = document.getElementById('reg-email');
    const passwordInput = document.getElementById('reg-password');
    const confirmInput = document.getElementById('reg-confirm');

    // Real-time validáció gépelés közben
    emailInput?.addEventListener('input', () => {
        if (!emailInput.value) {
            clearError(emailInput);
        } else if (!isValidEmail(emailInput.value)) {
            showError(emailInput, 'Adj meg egy érvényes email címet!');
        } else {
            showSuccess(emailInput);
        }
    });

    passwordInput?.addEventListener('input', () => {
        if (!passwordInput.value) {
            clearError(passwordInput);
        } else if (!isValidPassword(passwordInput.value)) {
            showError(passwordInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie!');
        } else {
            showSuccess(passwordInput);
        }
        // Megerősítés frissítése
        if (confirmInput?.value) {
            confirmInput.dispatchEvent(new Event('input'));
        }
    });

    confirmInput?.addEventListener('input', () => {
        if (!confirmInput.value) {
            clearError(confirmInput);
        } else if (confirmInput.value !== passwordInput?.value) {
            showError(confirmInput, 'A két jelszó nem egyezik meg!');
        } else {
            showSuccess(confirmInput);
        }
    });

    // Submit validáció
    registerForm.addEventListener('submit', (e) => {
        let valid = true;

        if (!emailInput?.value || !isValidEmail(emailInput.value)) {
            showError(emailInput, 'Adj meg egy érvényes email címet!');
            valid = false;
        }

        if (!passwordInput?.value || !isValidPassword(passwordInput.value)) {
            showError(passwordInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie!');
            valid = false;
        }

        if (!confirmInput?.value || confirmInput.value !== passwordInput?.value) {
            showError(confirmInput, 'A két jelszó nem egyezik meg!');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

// ============================================================
// LOGIN VALIDÁCIÓ
// ============================================================

const loginForm = document.getElementById('loginForm');
if (loginForm) {
    const emailInput = document.getElementById('login-email');
    const passwordInput = document.getElementById('login-password');

    emailInput?.addEventListener('input', () => {
        if (!emailInput.value) {
            clearError(emailInput);
        } else if (!isValidEmail(emailInput.value)) {
            showError(emailInput, 'Adj meg egy érvényes email címet!');
        } else {
            showSuccess(emailInput);
        }
    });

    passwordInput?.addEventListener('input', () => {
        if (passwordInput.value.length > 0) {
            showSuccess(passwordInput);
        } else {
            clearError(passwordInput);
        }
    });

    loginForm.addEventListener('submit', (e) => {
        let valid = true;

        if (!emailInput?.value || !isValidEmail(emailInput.value)) {
            showError(emailInput, 'Adj meg egy érvényes email címet!');
            valid = false;
        }

        if (!passwordInput?.value) {
            showError(passwordInput, 'Add meg a jelszavadat!');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

// ============================================================
// FORGOT PASSWORD VALIDÁCIÓ
// ============================================================

const forgotForm = document.getElementById('forgotForm');
if (forgotForm) {
    const emailInput = document.getElementById('forgot-email');

    emailInput?.addEventListener('input', () => {
        if (!emailInput.value) {
            clearError(emailInput);
        } else if (!isValidEmail(emailInput.value)) {
            showError(emailInput, 'Adj meg egy érvényes email címet!');
        } else {
            showSuccess(emailInput);
        }
    });

    forgotForm.addEventListener('submit', (e) => {
        if (!emailInput?.value || !isValidEmail(emailInput.value)) {
            showError(emailInput, 'Adj meg egy érvényes email címet!');
            e.preventDefault();
        }
    });
}

// ============================================================
// RESET PASSWORD VALIDÁCIÓ
// ============================================================

const resetForm = document.getElementById('resetForm');
if (resetForm) {
    const passwordInput = document.getElementById('reset-password');
    const confirmInput = document.getElementById('reset-confirm');

    passwordInput?.addEventListener('input', () => {
        if (!passwordInput.value) {
            clearError(passwordInput);
        } else if (!isValidPassword(passwordInput.value)) {
            showError(passwordInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie!');
        } else {
            showSuccess(passwordInput);
        }
        if (confirmInput?.value) {
            confirmInput.dispatchEvent(new Event('input'));
        }
    });

    confirmInput?.addEventListener('input', () => {
        if (!confirmInput.value) {
            clearError(confirmInput);
        } else if (confirmInput.value !== passwordInput?.value) {
            showError(confirmInput, 'A két jelszó nem egyezik meg!');
        } else {
            showSuccess(confirmInput);
        }
    });

    resetForm.addEventListener('submit', (e) => {
        let valid = true;

        if (!passwordInput?.value || !isValidPassword(passwordInput.value)) {
            showError(passwordInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie!');
            valid = false;
        }

        if (!confirmInput?.value || confirmInput.value !== passwordInput?.value) {
            showError(confirmInput, 'A két jelszó nem egyezik meg!');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
}

// ============================================================
// PROFILE - EMAIL VÁLTOZTATÁS VALIDÁCIÓ
// ============================================================

const profileEmailForm = document.getElementById('profileEmailForm');
if (profileEmailForm) {
    const newEmailInput = document.getElementById('profile-new-email');
    const passwordInput = document.getElementById('profile-email-password');

    newEmailInput?.addEventListener('input', () => {
        if (!newEmailInput.value) {
            clearError(newEmailInput);
        } else if (!isValidEmail(newEmailInput.value)) {
            showError(newEmailInput, 'Adj meg egy érvényes email címet!');
        } else {
            showSuccess(newEmailInput);
        }
    });

    profileEmailForm.addEventListener('submit', (e) => {
        let valid = true;

        if (!newEmailInput?.value || !isValidEmail(newEmailInput.value)) {
            showError(newEmailInput, 'Adj meg egy érvényes email címet!');
            valid = false;
        }

        if (!passwordInput?.value) {
            showError(passwordInput, 'Add meg a jelszavadat a megerősítéshez!');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
}

// ============================================================
// PROFILE - JELSZÓ VÁLTOZTATÁS VALIDÁCIÓ
// ============================================================

const profilePasswordForm = document.getElementById('profilePasswordForm');
if (profilePasswordForm) {
    const currentInput = document.getElementById('profile-current-password');
    const newInput = document.getElementById('profile-new-password');
    const confirmInput = document.getElementById('profile-confirm-password');

    newInput?.addEventListener('input', () => {
        if (!newInput.value) {
            clearError(newInput);
        } else if (!isValidPassword(newInput.value)) {
            showError(newInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie!');
        } else {
            showSuccess(newInput);
        }
        if (confirmInput?.value) {
            confirmInput.dispatchEvent(new Event('input'));
        }
    });

    confirmInput?.addEventListener('input', () => {
        if (!confirmInput.value) {
            clearError(confirmInput);
        } else if (confirmInput.value !== newInput?.value) {
            showError(confirmInput, 'A két jelszó nem egyezik meg!');
        } else {
            showSuccess(confirmInput);
        }
    });

    profilePasswordForm.addEventListener('submit', (e) => {
        let valid = true;

        if (!currentInput?.value) {
            showError(currentInput, 'Add meg a jelenlegi jelszavadat!');
            valid = false;
        }

        if (!newInput?.value || !isValidPassword(newInput.value)) {
            showError(newInput, 'A jelszónak legalább 6 karakter hosszúnak kell lennie!');
            valid = false;
        }

        if (!confirmInput?.value || confirmInput.value !== newInput?.value) {
            showError(confirmInput, 'A két jelszó nem egyezik meg!');
            valid = false;
        }

        if (!valid) e.preventDefault();
    });
}