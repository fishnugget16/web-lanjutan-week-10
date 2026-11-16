<?php
/**
 * File: components/footer.php
 * Template Footer - Linear Design System
 * Deep Canvas, Hairline Border, Pure Sans-Serif Typography
 *
 * Termasuk: Logout Confirmation Modal (Linear-themed, menggantikan confirm() bawaan browser)
 */
?>
</main>

<footer class="linear-footer">
    <div class="container-xl">
        <div class="row align-items-center gy-3">
            <div class="col-md-7 text-center text-md-start">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                    <span class="linear-brand-icon" style="width: 18px; height: 18px; font-size: 10px;">
                        <i class="bi bi-command"></i>
                    </span>
                    <span class="fw-semibold text-body">Student Portal</span>
                    <span class="text-tertiary">&bull;</span>
                    <span class="linear-muted">Universitas Kristen Krida Wacana</span>
                </div>
                <p class="mb-0 text-tertiary" style="font-size: 11px;">
                    Sistem Evaluasi &amp; Rekapitulasi Akademik Mahasiswa. Hak Cipta &copy; <?= date('Y') ?> UKRIDA.
                </p>
            </div>
            <div class="col-md-5 text-center text-md-end">
                <div class="d-inline-flex flex-wrap gap-2 justify-content-center justify-content-md-end align-items-center">
                    <span class="linear-pill linear-pill-default" style="font-size: 11px;">
                        <span class="linear-status-dot linear-status-dot-success me-1"></span> TA 2025/2026 Genap
                    </span>
                    <span class="linear-pill linear-pill-default" style="font-size: 11px;">
                        preview_admin_testing_demo
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- ============================================================
     Logout Confirmation Modal — Linear Design System
     Panel: surface-1 (#0f1011), hairline-strong border (#34343a),
     lavender accent pada ikon & tombol primary. Menggantikan
     confirm() bawaan browser agar konsisten dengan tema.
     ============================================================ -->
<div class="modal fade linear-modal" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content linear-modal-content">
            <!-- Header -->
            <div class="linear-modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="linear-modal-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <div>
                        <div class="linear-eyebrow mb-1">Konfirmasi Sesi</div>
                        <h5 class="linear-modal-title mb-0" id="logoutModalLabel">Keluar dari Portal?</h5>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="linear-modal-body">
                <p class="linear-modal-text mb-0">
                    Sesi akademik Anda akan diakhiri. Anda perlu login kembali untuk mengakses dashboard, profil, dan preferensi.
                </p>
            </div>

            <!-- Footer: aksi -->
            <div class="linear-modal-footer">
                <button type="button" class="btn-linear-ghost flex-fill" data-bs-dismiss="modal">
                    Batal
                </button>
                <a href="logout.php" class="btn-linear-primary flex-fill" id="confirmLogoutBtn">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5.3.3 Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Linear Interaction Script -->
<script src="assets/js/script.js"></script>

</body>
</html>