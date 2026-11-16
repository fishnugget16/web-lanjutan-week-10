<?php

/**
 * File: preference.php
 * Halaman Pengaturan Preferensi Tampilan - Linear Design System
 * - Tema Dark / Light (auto-save instan, tanpa tombol submit)
 * - Toggle Liquid Glass (efek material opsional, independen dari tema)
 * Pure Sans-Serif (Inter), Fully Responsive
 *
 * Catatan desain:
 * - Tanpa notifikasi sukses — switch tema = feedback visual instan.
 * - Hijau (#27a644) hanya untuk status pill, bukan banner.
 * - Lavender (#5e6ad2) hanya untuk brand mark, focus ring, CTA,
 *   border kartu tema aktif, dan toggle switch.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "functions.php";

cekLogin();

$themeAktif = getTheme();
$liquidGlassAktif = ($_COOKIE['liquid_glass'] ?? '0') === '1';

$pageTitle = "Pengaturan Preferensi";
require_once "components/header.php";
?>

<div class="container-xl py-4">
    <!-- Breadcrumb Nav -->
    <div class="d-flex align-items-center gap-2 mb-3 linear-muted small">
        <a href="dashboard.php" class="text-decoration-none linear-muted">
            <i class="bi bi-columns-gap me-1"></i> Dashboard
        </a>
        <span>/</span>
        <span class="text-body fw-medium">Preferensi</span>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="linear-card p-4 p-md-5">
                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom" style="border-color: var(--linear-hairline) !important;">
                    <div>
                        <div class="linear-eyebrow mb-1">Pengaturan UI &bull; Linear Design System</div>
                        <h2 class="h3 fw-semibold mb-0">Preferensi Antarmuka</h2>
                        <p class="linear-muted small mb-0">Pilih tema &amp; efek material &mdash; preferensi tersimpan otomatis</p>
                    </div>
                    <span class="linear-pill linear-pill-default" id="activeThemeBadge">
                        <?= strtoupper(htmlspecialchars($themeAktif)) ?>
                    </span>
                </div>

                <!-- Info User Sesi -->
                <div class="p-3 rounded-2 mb-4 d-flex align-items-center gap-3" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                    <div class="linear-avatar">
                        <?= htmlspecialchars(getInitials($_SESSION["nama"])) ?>
                    </div>
                    <div class="small">
                        <span class="linear-muted">Sesi Pengguna:</span>
                        <div class="fw-medium text-body"><?= htmlspecialchars($_SESSION["nama"]) ?> &bull; <span><?= htmlspecialchars($_SESSION["nim"]) ?></span></div>
                    </div>
                </div>

                <!-- ============================================================
                     Bagian 1: Pemilihan Tema (Dark / Light)
                     ============================================================ -->
                <div class="mb-4">
                    <label class="form-label small fw-medium mb-3">Mode Tampilan (Klik untuk langsung menerapkan &amp; menyimpan):</label>

                    <div class="row g-3">
                        <!-- Opsi Dark Mode (Linear Default) -->
                        <div class="col-12 col-md-6">
                            <label class="w-100 h-100">
                                <input
                                    type="radio"
                                    name="theme"
                                    value="dark"
                                    class="d-none theme-radio-input"
                                    <?= $themeAktif === "dark" ? "checked" : "" ?>>
                                <div class="linear-theme-card">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-moon-stars text-primary fs-5"></i>
                                            <div>
                                                <div class="fw-semibold small text-body">Linear Dark (Default)</div>
                                                <div class="linear-muted" style="font-size: 11px;">Kanvas #010102</div>
                                            </div>
                                        </div>
                                        <span class="linear-pill linear-pill-primary" style="font-size: 10px;">REKOMENDASI</span>
                                    </div>

                                    <!-- Mockup Box Dark -->
                                    <div class="theme-preview-box theme-preview-dark mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom" style="border-color: #23252a;">
                                            <span class="fw-semibold" style="color: #5e6ad2;">Student Portal</span>
                                            <span class="linear-pill linear-pill-success" style="font-size: 9px;">LULUS</span>
                                        </div>
                                        <div style="font-size: 11px; color: #8a8f98;">
                                            Kanvas hitam #010102 dengan hairline #23252a.
                                        </div>
                                    </div>
                                    <small class="linear-muted d-block" style="font-size: 11px;">
                                        Kenyamanan mata maksimal dan fokus tinggi untuk malam hari.
                                    </small>
                                </div>
                            </label>
                        </div>

                        <!-- Opsi Light Mode (Linear Inverse Clean) -->
                        <div class="col-12 col-md-6">
                            <label class="w-100 h-100">
                                <input
                                    type="radio"
                                    name="theme"
                                    value="light"
                                    class="d-none theme-radio-input"
                                    <?= $themeAktif === "light" ? "checked" : "" ?>>
                                <div class="linear-theme-card">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-sun text-warning fs-5"></i>
                                            <div>
                                                <div class="fw-semibold small text-body">Linear Light (Studio)</div>
                                                <div class="linear-muted" style="font-size: 11px;">Kanvas Bersih #fafbfc</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mockup Box Light -->
                                    <div class="theme-preview-box theme-preview-light mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom" style="border-color: #e3e6ec;">
                                            <span class="fw-semibold" style="color: #5e6ad2;">Student Portal</span>
                                            <span class="linear-pill linear-pill-success" style="font-size: 9px;">LULUS</span>
                                        </div>
                                        <div style="font-size: 11px; color: #6b7280;">
                                            Kanvas terang dengan kontras tajam.
                                        </div>
                                    </div>
                                    <small class="linear-muted d-block" style="font-size: 11px;">
                                        Optimal untuk ruangan dengan pencahayaan terang di siang hari.
                                    </small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- ============================================================
                     Bagian 2: Efek Material — Liquid Glass (toggle independen)
                     Bisa aktif di Dark maupun Light. Base tetap Linear.
                     ============================================================ -->
                <div class="linear-card" style="background-color: var(--linear-surface-2);">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="linear-modal-icon" style="width: 36px; height: 36px; font-size: 16px;">
                                <i class="bi bi-droplet-half"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small text-body">Efek Material: Liquid Glass</div>
                                <div class="linear-muted" style="font-size: 11px;">
                                    Lapisan kaca opsional di atas Linear base &bull; bisa aktif di Dark maupun Light
                                </div>
                            </div>
                        </div>

                        <!-- Toggle Switch -->
                        <div class="form-check form-switch m-0 p-0 d-flex align-items-center" style="min-height: auto;">
                            <input
                                class="form-check-input linear-toggle-switch m-0"
                                type="checkbox"
                                role="switch"
                                id="liquidGlassToggle"
                                <?= $liquidGlassAktif ? 'checked' : '' ?>>
                            <label class="visually-hidden" for="liquidGlassToggle">Aktifkan Liquid Glass</label>
                        </div>
                    </div>

                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-md-7">
                            <p class="linear-muted small mb-2">
                                Menambahkan <span class="text-body">backdrop blur</span>,
                                <span class="text-body">iridescent border</span>, dan
                                <span class="text-body">transisi 480ms</span> ke seluruh panel, tombol,
                                input, dan modal. Base tetap Linear: surface ladder, lavender accent,
                                dan hairline tidak berubah.
                            </p>
                            <div class="d-flex flex-wrap gap-3 small linear-muted">
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check2 text-success"></i> Bisa di Dark &amp; Light
                                </span>
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check2 text-success"></i> Toggle instan
                                </span>
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-dash-circle text-tertiary"></i> Tidak berlaku di Landing
                                </span>
                            </div>
                        </div>
                        <div class="col-12 col-md-5">
                            <!-- Preview mini: kartu dengan efek kaca -->
                            <div class="liquid-glass-preview <?= $liquidGlassAktif ? 'is-active' : '' ?>" id="liquidGlassPreview">
                                <div class="liquid-glass-preview-inner">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold" style="font-size: 11px; color: var(--linear-primary-hover);">Preview</span>
                                        <span class="linear-pill linear-pill-success" style="font-size: 9px;">KARTU</span>
                                    </div>
                                    <div class="linear-muted" style="font-size: 10.5px;">
                                        Kartu contoh dengan efek kaca.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status text kecil -->
                    <div class="linear-muted mt-3 pt-2 border-top" style="border-color: var(--linear-hairline) !important; font-size: 11px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Status: <span id="liquidGlassStatus" class="fw-medium text-body">
                            <?= $liquidGlassAktif ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once "components/footer.php"; ?>