<?php

/**
 * File: landing.php
 * Landing Page - Student Portal UKRIDA
 * Linear Design System: canvas #010102, surface ladder, lavender accent,
 * hairline borders, Inter dengan negative tracking.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "functions.php";

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION["isLogin"]) && $_SESSION["isLogin"] === true) {
    header("Location: dashboard.php");
    exit;
}

$pageTitle = "Student Portal — Evaluasi Akademik";
$hideNavbar = true;   // landing pakai navbar sendiri
require_once "components/header.php";
?>

<!-- ==========================================================================
     Landing Top Nav (minimal, brand + CTA masuk)
     ========================================================================== -->
<nav class="linear-nav">
    <div class="container-xl d-flex align-items-center justify-content-between">
        <a href="landing.php" class="linear-brand">
            <span class="linear-brand-icon">
                <i class="bi bi-command"></i>
            </span>
            <span>Student Portal</span>
            <span class="linear-pill linear-pill-default" style="font-size: 10px;">UKRIDA</span>
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="#fitur" class="btn-linear-ghost d-none d-md-inline-flex">Fitur</a>
            <a href="#cara-kerja" class="btn-linear-ghost d-none d-md-inline-flex">Cara Kerja</a>
            <a href="login.php" class="btn-linear-primary">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </a>
        </div>
    </div>
</nav>

<!-- ==========================================================================
     Hero Section — display headline, negative tracking, lavender CTA
     ========================================================================== -->
<section class="container-xl py-5" style="padding-top: 72px !important; padding-bottom: 72px !important;">
    <div class="row align-items-center g-5">
        <div class="col-12 col-lg-6">
            <div class="linear-eyebrow mb-3">
                <span class="linear-status-dot linear-status-dot-success"></span>
                Portal Akademik &bull; TA 2025/2026
            </div>

            <h1 class="mb-3" style="font-size: clamp(32px, 5vw, 52px); line-height: 1.05; letter-spacing: -0.035em; font-weight: 600;">
                Evaluasi akademik yang<br>
                <span class="text-primary">presisi dan transparan.</span>
            </h1>

            <p class="linear-muted mb-4" style="font-size: 16px; line-height: 1.6; max-width: 520px;">
                Student Portal UKRIDA menyatukan nilai tugas, UTS, UAS, kehadiran, dan catatan dosen
                dalam satu dashboard yang bersih — dirancang untuk membantu Anda memahami capaian
                studi tanpa kebisingan visual.
            </p>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="login.php" class="btn-linear-primary py-2 px-3">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk ke Portal
                </a>
                <a href="#fitur" class="btn-linear-secondary py-2 px-3">
                    <i class="bi bi-grid"></i> Lihat Fitur
                </a>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3 small linear-muted">
                <span class="d-inline-flex align-items-center gap-1">
                    <i class="bi bi-shield-check text-success"></i> Data nilai resmi
                </span>
                <span class="d-inline-flex align-items-center gap-1">
                    <i class="bi bi-moon-stars text-primary"></i> Dark &amp; light mode
                </span>
                <span class="d-inline-flex align-items-center gap-1">
                    <i class="bi bi-phone"></i> Responsif
                </span>
            </div>
        </div>

        <!-- Product Preview Panel — mockup dashboard dalam surface-1 -->
        <div class="col-12 col-lg-6">
            <div class="linear-card" style="padding: 20px; border-radius: var(--linear-radius-xl);">
                <!-- Fake window bar -->
                <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom" style="border-color: var(--linear-hairline) !important;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #34343a; display: inline-block;"></span>
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #34343a; display: inline-block;"></span>
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #34343a; display: inline-block;"></span>
                    <span class="linear-muted ms-2" style="font-size: 11px;">student-portal.ukrida.ac.id/dashboard</span>
                </div>

                <!-- Mini KPI row -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-3 rounded-2" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                            <div class="linear-eyebrow mb-1">Nilai Akhir</div>
                            <div class="h4 mb-0 fw-bold">86.40</div>
                            <span class="linear-pill linear-pill-success mt-1" style="font-size: 10px;">Grade A</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-2" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                            <div class="linear-eyebrow mb-1">Kehadiran</div>
                            <div class="h4 mb-0 fw-bold">92%</div>
                            <div class="linear-progress mt-2">
                                <div class="linear-progress-bar linear-progress-bar-success" style="width: 92%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mini component rows -->
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                        <span class="small text-body">Tugas &amp; Proyek</span>
                        <span class="linear-pill linear-pill-default" style="font-size: 10px;">30% &bull; 88</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                        <span class="small text-body">UTS</span>
                        <span class="linear-pill linear-pill-default" style="font-size: 10px;">30% &bull; 82</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-2" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                        <span class="small text-body">UAS</span>
                        <span class="linear-pill linear-pill-default" style="font-size: 10px;">40% &bull; 88</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     Feature Grid — 4 kolom, surface-1 cards dengan hairline
     ========================================================================== -->
<section id="fitur" class="container-xl py-5" style="padding-top: 56px !important; padding-bottom: 56px !important;">
    <div class="text-center mb-5">
        <div class="linear-eyebrow mb-2">Fitur Utama</div>
        <h2 class="mb-2" style="letter-spacing: -0.03em;">Dirancang untuk kejelasan akademik.</h2>
        <p class="linear-muted mb-0" style="max-width: 560px; margin: 0 auto;">
            Setiap fitur dibangun untuk menghadirkan data nilai secara utuh tanpa elemen dekoratif yang mengganggu.
        </p>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="linear-card linear-card-interactive h-100">
                <div class="linear-modal-icon mb-3" style="width: 36px; height: 36px; font-size: 16px;">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <h4 class="h5 fw-semibold mb-2">Rincian Nilai</h4>
                <p class="linear-muted small mb-0">
                    Transparansi bobot 30/30/40 antara tugas, UTS, dan UAS. Setiap poin bisa dilacak.
                </p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="linear-card linear-card-interactive h-100">
                <div class="linear-modal-icon mb-3" style="width: 36px; height: 36px; font-size: 16px;">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <h4 class="h5 fw-semibold mb-2">Kehadiran Kuliah</h4>
                <p class="linear-muted small mb-0">
                    Monitoring persentase kehadiran dengan ambang minimum 75% untuk kelayakan ujian.
                </p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="linear-card linear-card-interactive h-100">
                <div class="linear-modal-icon mb-3" style="width: 36px; height: 36px; font-size: 16px;">
                    <i class="bi bi-award"></i>
                </div>
                <h4 class="h5 fw-semibold mb-2">Predikat &amp; Grade</h4>
                <p class="linear-muted small mb-0">
                    Perhitungan grade huruf dan predikat prestasi otomatis berdasarkan capaian akhir.
                </p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="linear-card linear-card-interactive h-100">
                <div class="linear-modal-icon mb-3" style="width: 36px; height: 36px; font-size: 16px;">
                    <i class="bi bi-chat-left-text"></i>
                </div>
                <h4 class="h5 fw-semibold mb-2">Catatan Dosen</h4>
                <p class="linear-muted small mb-0">
                    Evaluasi kualitatif dari dosen pembimbing yang menyertai setiap capaian kuantitatif.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     How It Works — 3 langkah, surface-2 panel
     ========================================================================== -->
<section id="cara-kerja" class="container-xl py-5" style="padding-top: 56px !important; padding-bottom: 56px !important;">
    <div class="linear-card p-4 p-md-5">
        <div class="text-center mb-5">
            <div class="linear-eyebrow mb-2">Cara Kerja</div>
            <h2 class="mb-0" style="letter-spacing: -0.03em;">Tiga langkah, tanpa hambatan.</h2>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <span class="linear-avatar" style="background-color: var(--linear-primary-subtle); border-color: rgba(94,106,210,0.3); color: var(--linear-primary-hover);">1</span>
                    <div>
                        <h5 class="mb-1 fw-semibold">Login dengan akun kampus</h5>
                        <p class="linear-muted small mb-0">
                            Gunakan email resmi <span class="text-body">@ukrida.ac.id</span> dan password Anda.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <span class="linear-avatar" style="background-color: var(--linear-primary-subtle); border-color: rgba(94,106,210,0.3); color: var(--linear-primary-hover);">2</span>
                    <div>
                        <h5 class="mb-1 fw-semibold">Tinjau capaian studi</h5>
                        <p class="linear-muted small mb-0">
                            Dashboard menampilkan nilai, kehadiran, dan status kelulusan secara langsung.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="d-flex align-items-start gap-3">
                    <span class="linear-avatar" style="background-color: var(--linear-primary-subtle); border-color: rgba(94,106,210,0.3); color: var(--linear-primary-hover);">3</span>
                    <div>
                        <h5 class="mb-1 fw-semibold">Atur preferensi tampilan</h5>
                        <p class="linear-muted small mb-0">
                            Pilih mode gelap atau terang sesuai kenyamanan Anda &mdash; tersimpan otomatis.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     Final CTA — surface-1 banner dengan lavender primary
     ========================================================================== -->
<section class="container-xl py-5" style="padding-top: 40px !important; padding-bottom: 72px !important;">
    <div class="linear-card p-4 p-md-5 text-center" style="background-color: var(--linear-surface-2);">
        <div class="linear-eyebrow mb-2">Siap Memulai</div>
        <h2 class="mb-3" style="letter-spacing: -0.03em; max-width: 620px; margin-left: auto; margin-right: auto;">
            Masuk dan lihat capaian studi Anda dalam satu tampilan.
        </h2>
        <p class="linear-muted mb-4" style="max-width: 480px; margin-left: auto; margin-right: auto;">
            Portal ini menggunakan akun demo untuk keperluan demonstrasi &mdash; coba salah satu akun
            tersedia di halaman login.
        </p>
        <div class="d-inline-flex flex-wrap gap-2 justify-content-center">
            <a href="login.php" class="btn-linear-primary py-2 px-4">
                <i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang
            </a>
        </div>
    </div>
</section>

<?php require_once "components/footer.php"; ?>