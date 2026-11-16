<?php

/**
 * File: profile.php
 * Halaman Profil Mahasiswa - Linear Design System
 * Connected to MySQL Database
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "functions.php";

// Proteksi login
cekLogin();

// Ambil data detail mahasiswa aktif berdasarkan NIM dari MySQL
$user = ambilMahasiswaByNim($conn, $_SESSION["nim"]);

if ($user === null) {
    header("Location: logout.php");
    exit;
}

// Konversi variabel numerik
$nilaiTugas = (float) $user["nilai_tugas"];
$nilaiUTS   = (float) $user["nilai_uts"];
$nilaiUAS   = (float) $user["nilai_uas"];
$kehadiran  = (int) $user["kehadiran"];

// Kalkulasi capaian belajar
$nilaiAkhir      = hitungNilaiAkhir($nilaiTugas, $nilaiUTS, $nilaiUAS);
$grade           = tentukanGrade($nilaiAkhir);
$statusKelulusan = tentukanStatusKelulusan($nilaiAkhir, $kehadiran);
$predikat        = tentukanPredikat($nilaiAkhir, $kehadiran);

$pageTitle = "Profil Mahasiswa";
require_once "components/header.php";
?>

<div class="container-xl py-4">
    <!-- Breadcrumb Nav -->
    <div class="d-flex align-items-center gap-2 mb-3 linear-muted small">
        <a href="dashboard.php" class="text-decoration-none linear-muted">
            <i class="bi bi-columns-gap me-1"></i> Dashboard
        </a>
        <span>/</span>
        <span class="text-body fw-medium">Profil Mahasiswa</span>
    </div>

    <!-- Header Profil Card (Responsive) -->
    <div class="linear-card mb-4 p-4">
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4 text-center text-md-start">
            <div class="linear-avatar-lg flex-shrink-0">
                <?= htmlspecialchars(getInitials($user["nama"])) ?>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
                    <h2 class="h3 fw-semibold mb-0"><?= htmlspecialchars($user["nama"]) ?></h2>
                    <span class="linear-pill linear-pill-success">
                        <span class="linear-status-dot linear-status-dot-success"></span> Aktif
                    </span>
                    <span class="linear-pill linear-pill-default">
                        <?= htmlspecialchars($user["nama_prodi"]) ?>
                    </span>
                </div>
                <p class="linear-muted mb-3 small">
                    Fakultas Teknik &amp; Ilmu Komputer &bull; Universitas Kristen Krida Wacana
                </p>
                <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2">
                    <button type="button" class="btn-linear-secondary py-1 px-2 btn-copy-clipboard" data-copy="<?= htmlspecialchars($user["nim"]) ?>" title="Salin NIM">
                        <i class="bi bi-clipboard text-secondary me-1"></i>
                        <span class="small">NIM: <?= htmlspecialchars($user["nim"]) ?></span>
                    </button>
                    <button type="button" class="btn-linear-secondary py-1 px-2 btn-copy-clipboard" data-copy="<?= htmlspecialchars($user["email"]) ?>" title="Salin Email">
                        <i class="bi bi-envelope text-secondary me-1"></i>
                        <span class="small"><?= htmlspecialchars($user["email"]) ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2-Column Detail Grid -->
    <div class="row g-4">
        <!-- Kolom Kiri: Biodata Lengkap -->
        <div class="col-12 col-lg-6">
            <div class="linear-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                    <span class="linear-eyebrow">Identitas Kemahasiswaan</span>
                    <span class="linear-pill linear-pill-default">BIODATA</span>
                </div>

                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                        <span class="linear-muted small">Nomor Induk Mahasiswa</span>
                        <span class="fw-medium text-body"><?= htmlspecialchars($user["nim"]) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                        <span class="linear-muted small">Nama Lengkap</span>
                        <span class="fw-medium text-body"><?= htmlspecialchars($user["nama"]) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                        <span class="linear-muted small">Email Resmi</span>
                        <span class="fw-medium text-body"><?= htmlspecialchars($user["email"]) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                        <span class="linear-muted small">Program Studi</span>
                        <span class="fw-medium text-body"><?= htmlspecialchars($user["nama_prodi"]) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                        <span class="linear-muted small">Fakultas / Kampus</span>
                        <span class="fw-medium text-body">FTIK &bull; Kampus UKRIDA</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="linear-muted small">Tahun Masuk</span>
                        <span class="linear-pill linear-pill-default">Angkatan <?= htmlspecialchars($user["tahun_masuk"]) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Rangkuman Transkrip Nilai -->
        <div class="col-12 col-lg-6">
            <div class="linear-card p-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                    <span class="linear-eyebrow">Rekapitulasi Capaian Belajar</span>
                    <span class="linear-pill linear-pill-default">TRANSKRIP</span>
                </div>

                <!-- 4 Box Mini Skor -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-3 rounded-2 text-center" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                            <span class="linear-muted small d-block">Tugas (30%)</span>
                            <span class="fs-5 fw-bold text-body"><?= $nilaiTugas ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-2 text-center" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                            <span class="linear-muted small d-block">UTS (30%)</span>
                            <span class="fs-5 fw-bold text-body"><?= $nilaiUTS ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-2 text-center" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                            <span class="linear-muted small d-block">UAS (40%)</span>
                            <span class="fs-5 fw-bold text-body"><?= $nilaiUAS ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-2 text-center" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                            <span class="linear-muted small d-block">Kehadiran</span>
                            <span class="fs-5 fw-bold text-body"><?= $kehadiran ?>%</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 pt-2 border-top" style="border-color: var(--linear-hairline) !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="linear-muted small">Nilai Akhir Terakumulasi:</span>
                        <strong class="fs-6 text-primary"><?= number_format($nilaiAkhir, 2) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="linear-muted small">Grade Huruf:</span>
                        <span class="linear-pill <?= getGradePillClass($grade) ?>">Grade <?= $grade ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="linear-muted small">Status Kelulusan:</span>
                        <span class="linear-pill <?= getStatusPillClass($statusKelulusan) ?>"><?= $statusKelulusan ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="linear-muted small">Predikat Prestasi:</span>
                        <span class="linear-pill <?= getPredikatPillClass($predikat) ?>"><?= $predikat ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "components/footer.php"; ?>