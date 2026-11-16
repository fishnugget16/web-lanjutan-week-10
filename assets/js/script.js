/**
 * File: assets/js/script.js
 * Linear UI Interactions
 * - Password visibility toggle
 * - Demo credentials autofill
 * - Instant theme auto-save (no toast — visual state is the feedback)
 * - Copy to clipboard
 * - Logout confirmation modal (replaces native confirm())
 * - Auto-dismiss alerts
 * - Liquid Glass toggle (material layer, independent from theme)
 * - Idle timeout — redirect ke login.php setelah 60 detik idle
 */

document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // 1. Password Visibility Toggle (Login Page)
    // ============================================================
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye', !isPassword);
                icon.classList.toggle('bi-eye-slash', isPassword);
            }
        });
    }

    // ============================================================
    // 2. Quick Demo Account Autofill (Login Page)
    //    No flash — autofill senyap tanpa border accent.
    // ============================================================
    const demoChips = document.querySelectorAll('.demo-chip');
    demoChips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            const email = this.getAttribute('data-email');
            const pass = this.getAttribute('data-password');

            const emailInput = document.getElementById('email');
            const passInput = document.getElementById('password');

            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = pass;
                passInput.focus();
            }
        });
    });

    // ============================================================
    // 3. Live Theme Switcher with Instant Auto-Save (Preference Page)
    //    No toast — border/box-shadow dari radio:checked adalah
    //    satu-satunya feedback visual (sesuai DESIGN.md).
    //    Cookie-only state, path=/.
    // ============================================================
    const themeRadios = document.querySelectorAll('input[name="theme"]');
    themeRadios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            const selectedTheme = this.value;

            // Terapkan tema ke root html secara real-time
            document.documentElement.setAttribute('data-bs-theme', selectedTheme);

            // Hapus cookie lama di path lain (bersihkan duplikat)
            document.cookie = "theme=; path=/; max-age=0";
            document.cookie = "theme=; path=/pertemuan5_tugas; max-age=0";
            document.cookie = "theme=; max-age=0";

            // Set cookie baru di path root (30 hari)
            const maxAge = 86400 * 30;
            document.cookie = "theme=" + encodeURIComponent(selectedTheme)
                + "; path=/; max-age=" + maxAge + "; SameSite=Lax";

            // Perbarui badge tema aktif (satu-satunya feedback tekstual)
            const currentBadge = document.getElementById('activeThemeBadge');
            if (currentBadge) {
                currentBadge.textContent = selectedTheme.toUpperCase();
            }
        });
    });

    // ============================================================
    // 4. Copy to Clipboard (Profile Page)
    // ============================================================
    const copyButtons = document.querySelectorAll('.btn-copy-clipboard');
    copyButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetText = this.getAttribute('data-copy');
            if (!targetText) return;

            navigator.clipboard.writeText(targetText).then(() => {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check2 text-success"></i> Tersalin';
                this.style.borderColor = 'var(--linear-semantic-success)';

                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.style.borderColor = '';
                }, 1800);
            }).catch(err => {
                console.error('Clipboard copy failed:', err);
            });
        });
    });

    // ============================================================
    // 5. Logout Confirmation Modal (Linear Design System)
    //    Menggantikan confirm() bawaan browser dengan modal kustom
    //    yang mengikuti surface ladder & hairline DESIGN.md.
    // ============================================================
    const logoutModalEl = document.getElementById('logoutModal');
    const logoutLinks = document.querySelectorAll('.btn-confirm-logout, a[href="logout.php"]');

    if (logoutModalEl && logoutLinks.length) {
        const logoutModal = new bootstrap.Modal(logoutModalEl, {
            backdrop: 'static',
            keyboard: true
        });

        logoutLinks.forEach(function (link) {
            // Skip tombol konfirmasi di dalam modal — dia punya handler sendiri
            if (link.id === 'confirmLogoutBtn') return;

            link.addEventListener('click', function (e) {
                e.preventDefault();
                logoutModal.show();
            });
        });

        // Tombol "Keluar" di dalam modal: tutup modal, lalu redirect
        const confirmBtn = document.getElementById('confirmLogoutBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function (e) {
                e.preventDefault();
                logoutModal.hide();
                setTimeout(() => {
                    window.location.href = 'logout.php';
                }, 180);
            });
        }
    }

    // ============================================================
    // 6. Auto-dismiss Alerts
    // ============================================================
    const alerts = document.querySelectorAll('.alert-auto-dismiss');
    alerts.forEach(function (alertEl) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertEl);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 4000);
    });

    // ============================================================
    // 7. Liquid Glass Toggle (Preference Page)
    //    Cookie-only state, path=/. Explicit cleanup dulu untuk
    //    menghindari duplikat cookie dari versi lama.
    // ============================================================
    const liquidGlassToggle = document.getElementById('liquidGlassToggle');
    const liquidGlassStatus = document.getElementById('liquidGlassStatus');
    const liquidGlassPreview = document.getElementById('liquidGlassPreview');

    if (liquidGlassToggle) {
        // Sinkronkan preview & status dengan state checkbox yang dirender PHP
        const syncLiquidGlassUI = (aktif) => {
            if (liquidGlassPreview) {
                liquidGlassPreview.classList.toggle('is-active', aktif);
            }
            if (liquidGlassStatus) {
                liquidGlassStatus.textContent = aktif ? 'Aktif' : 'Nonaktif';
            }
        };
        syncLiquidGlassUI(liquidGlassToggle.checked);

        liquidGlassToggle.addEventListener('change', function () {
            const aktif = this.checked;

            // 1. Apply ke root html (langsung, tanpa reload)
            document.documentElement.setAttribute('data-liquid-glass', aktif ? 'true' : 'false');

            // 2. Hapus cookie lama di path lain (bersihkan duplikat)
            document.cookie = "liquid_glass=; path=/; max-age=0";
            document.cookie = "liquid_glass=; path=/pertemuan5_tugas; max-age=0";
            document.cookie = "liquid_glass=; max-age=0";

            // 3. Set cookie baru di path root (30 hari)
            const maxAge = 86400 * 30;
            document.cookie = "liquid_glass=" + (aktif ? '1' : '0')
                + "; path=/; max-age=" + maxAge + "; SameSite=Lax";

            // 4. Update status text & preview
            syncLiquidGlassUI(aktif);
        });
    }

    // ============================================================
    // 8. Idle Timeout — redirect ke login.php setelah 60 detik
    //    idle (tanpa mouse / keyboard / scroll / touch).
    //    Sinkron dengan cekIdleTimeout() di functions.php.
    // ============================================================
    (function setupIdleTimeout() {
        // Hanya aktif kalau user sudah login
        // (login.php & landing.php tidak punya <nav class="linear-nav">)
        const isLoggedIn = document.querySelector('nav.linear-nav') !== null;
        if (!isLoggedIn) return;

        const IDLE_TIMEOUT = 60 * 1000; // 60 detik dalam ms
        let idleTimer = null;

        const redirectToLogin = () => {
            window.location.href = 'login.php';
        };

        const resetIdleTimer = () => {
            if (idleTimer) clearTimeout(idleTimer);
            idleTimer = setTimeout(redirectToLogin, IDLE_TIMEOUT);
        };

        // Event apapun dari user → reset timer
        const events = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click', 'wheel'];
        events.forEach(evt => {
            document.addEventListener(evt, resetIdleTimer, { passive: true });
        });

        // Mulai timer saat halaman load
        resetIdleTimer();

        // Kalau tab di-background lama, cek saat user balik
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                resetIdleTimer();
            }
        });
    })();

});