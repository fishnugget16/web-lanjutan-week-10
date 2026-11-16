<?php

/**
 * File: dashboard.php
 * Halaman Utama Student Portal - Linear Design System
 * Connected to MySQL Database
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "functions.php";

// Proteksi login
cekLogin();

// Ambil data mahasiswa yang sedang login dari MySQL
$user = ambilMahasiswaByNim($conn,$_SESSION["nim"]);

if ($user === null) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Kalkulasi Akademik berdasarkan data dari Database
$nilaiTugas = (float)$user["nilai_tugas"];
$nilaiUTS   = (float)$user["nilai_uts"];
$nilaiUAS   = (float)$user["nilai_uas"];
$kehadiran  = (int)$user["kehadiran"];

$nilaiAkhir      = hitungNilaiAkhir($nilaiTugas, $nilaiUTS,$nilaiUAS);
$grade           = tentukanGrade($nilaiAkhir);
$statusKelulusan = tentukanStatusKelulusan($nilaiAkhir, $kehadiran);$predikat        = tentukanPredikat($nilaiAkhir,$kehadiran);
$pesan           = buatPesan($grade);
$remedial        = perluRemedial($nilaiAkhir);

$pageTitle = "Dashboard";
require_once "components/header.php";
?>

<div class="container-xl py-4">
    <!-- Workspace Header -->
    <div class="mb-4 pb-3 border-bottom" style="border-color: var(--linear-hairline) !important;">
        <div class="linear-eyebrow mb-1">
            <span class="linear-status-dot linear-status-dot-success"></span> Mahasiswa Aktif &bull; TA <?= $user["tahun_masuk"] ?>/<?= $user["tahun_masuk"] + 1 ?>
        </div>
        <h1 class="h2 fw-semibold mb-1">Selamat Datang, <?= htmlspecialchars($user["nama"]) ?></h1>
        <div class="d-flex flex-wrap align-items-center gap-2 linear-muted small">
            <span>NIM: <strong class="text-body"><?= htmlspecialchars($user["nim"]) ?></strong></span>
            <span>&bull;</span>
            <span>Program Studi: <strong class="text-body"><?= htmlspecialchars($user["nama_prodi"]) ?></strong></span>
            <span>&bull;</span>
            <span>UKRIDA Jakarta</span>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Metric 1: Nilai Akhir -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="linear-card linear-card-interactive h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="linear-eyebrow">Nilai Akhir</span>
                    <span class="linear-pill <?= getGradePillClass($grade) ?>">
                        Grade <?= $grade ?>
                    </span>
                </div>
                <div class="my-1">
                    <div class="h2 mb-0 fw-bold"><?= number_format($nilaiAkhir, 2) ?></div>
                </div>
                <div class="linear-muted" style="font-size: 11.5px;">
                    Bobot: 30% Tugas, 30% UTS, 40% UAS
                </div>
            </div>
        </div>

        <!-- Metric 2: Status Kelulusan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="linear-card linear-card-interactive h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="linear-eyebrow">Status Akademik</span>
                    <span class="linear-pill linear-pill-default">Syarat &ge; 60</span>
                </div>
                <div class="my-1">
                    <span class="linear-pill <?= getStatusPillClass($statusKelulusan) ?> fs-6 py-1 px-3">
                        <span class="linear-status-dot <?= $statusKelulusan === 'LULUS' ? 'linear-status-dot-success' : 'linear-status-dot-danger' ?> me-1"></span>
                        <?= $statusKelulusan ?>
                    </span>
                </div>
                <div class="linear-muted" style="font-size: 11.5px;">
                    Kombinasi nilai akhir &amp; kehadiran kuliah
                </div>
            </div>
        </div>

        <!-- Metric 3: Presensi Kehadiran -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="linear-card linear-card-interactive h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="linear-eyebrow">Kehadiran Kuliah</span>
                    <span class="linear-pill <?= $kehadiran >= 75 ? 'linear-pill-success' : 'linear-pill-danger' ?>">
                        <?= $kehadiran >= 75 ? 'Memenuhi' : 'Kurang' ?>
                    </span>
                </div>
                <div class="my-1">
                    <div class="h2 mb-1 fw-bold"><?= $kehadiran ?>%</div>
                    <div class="linear-progress">
                        <div class="linear-progress-bar <?= $kehadiran >= 75 ? 'linear-progress-bar-success' : 'linear-progress-bar-danger' ?>" style="width: <?= $kehadiran ?>%;"></div>
                    </div>
                </div>
                <div class="linear-muted" style="font-size: 11.5px;">
                    Batas minimum kelayakan ujian: 75%
                </div>
            </div>
        </div>

        <!-- Metric 4: Predikat & Evaluasi -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="linear-card linear-card-interactive h-100 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="linear-eyebrow">Predikat</span>
                    <span class="linear-pill <?= getPredikatPillClass($predikat) ?>">
                        <?= $predikat ?>
                    </span>
                </div>
                <div class="my-1">
                    <?php if ($remedial): ?>
                        <span class="linear-pill linear-pill-danger">
                            <i class="bi bi-arrow-repeat me-1"></i> Wajib Remedial
                        </span>
                    <?php else: ?>
                        <span class="linear-pill linear-pill-success">
                            <i class="bi bi-shield-check me-1"></i> Bebas Remedial
                        </span>
                    <?php endif; ?>
                </div>
                <div class="linear-muted" style="font-size: 11.5px;">
                    Evaluasi capaian komprehensif semester
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- Kolom Kiri: Rincian Penilaian -->
        <div class="col-lg-8">
            <div class="linear-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                    <div>
                        <h3 class="h4 mb-0 fw-semibold">Rincian Komponen Penilaian</h3>
                        <p class="linear-muted small mb-0">Transparansi perhitungan capaian hasil studi per indikator</p>
                    </div>
                    <span class="linear-pill linear-pill-default">BOBOT RESMI</span>
                </div>

                <div class="table-responsive">
                    <table class="linear-table">
                        <thead>
                            <tr>
                                <th>Komponen Evaluasi</th>
                                <th class="text-center">Bobot</th>
                                <th class="text-center">Skor</th>
                                <th class="text-center">Poin</th>
                                <th>Distribusi Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Tugas -->
                            <tr>
                                <td>
                                    <div class="fw-medium text-body">Tugas &amp; Proyek Mandiri</div>
                                    <div class="linear-muted" style="font-size: 11.5px;">Penugasan mingguan &amp; praktikum</div>
                                </td>
                                <td class="text-center">
                                    <span class="linear-pill linear-pill-default">30%</span>
                                </td>
                                <td class="text-center fw-semibold text-body"><?= $nilaiTugas ?></td>
                                <td class="text-center fw-semibold text-primary"><?= number_format($nilaiTugas * 0.30, 2) ?></td>
                                <td style="width: 25%;">
                                    <div class="linear-progress">
                                        <div class="linear-progress-bar" style="width: <?= $nilaiTugas ?>%;"></div>
                                    </div>
                                </td>
                            </tr>

                            <!-- UTS -->
                            <tr>
                                <td>
                                    <div class="fw-medium text-body">Ujian Tengah Semester (UTS)</div>
                                    <div class="linear-muted" style="font-size: 11.5px;">Evaluasi teori &amp; pemahaman modul 1-7</div>
                                </td>
                                <td class="text-center">
                                    <span class="linear-pill linear-pill-default">30%</span>
                                </td>
                                <td class="text-center fw-semibold text-body"><?= $nilaiUTS ?></td>
                                <td class="text-center fw-semibold text-primary"><?= number_format($nilaiUTS * 0.30, 2) ?></td>
                                <td>
                                    <div class="linear-progress">
                                        <div class="linear-progress-bar" style="width: <?= $nilaiUTS ?>%;"></div>
                                    </div>
                                </td>
                            </tr>

                            <!-- UAS -->
                            <tr>
                                <td>
                                    <div class="fw-medium text-body">Ujian Akhir Semester (UAS)</div>
                                    <div class="linear-muted" style="font-size: 11.5px;">Evaluasi akhir implementasi sistem</div>
                                </td>
                                <td class="text-center">
                                    <span class="linear-pill linear-pill-default">40%</span>
                                </td>
                                <td class="text-center fw-semibold text-body"><?= $nilaiUAS ?></td>
                                <td class="text-center fw-semibold text-primary"><?= number_format($nilaiUAS * 0.40, 2) ?></td>
                                <td>
                                    <div class="linear-progress">
                                        <div class="linear-progress-bar" style="width: <?= $nilaiUAS ?>%;"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="fw-semibold">Total Nilai Akhir</td>
                                <td class="text-center fw-semibold">100%</td>
                                <td class="text-center linear-muted">&mdash;</td>
                                <td class="text-center fw-bold text-primary fs-6"><?= number_format($nilaiAkhir, 2) ?></td>
                                <td>
                                    <span class="linear-pill <?= getGradePillClass($grade) ?>">
                                        Grade <?= $grade ?> (<?= $statusKelulusan ?>)
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Catatan Evaluasi -->
        <div class="col-lg-4">
            <div class="linear-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                        <div>
                            <span class="linear-eyebrow">Evaluasi Dosen</span>
                            <h4 class="h5 mb-0 fw-semibold">Catatan Pembimbing</h4>
                        </div>
                        <i class="bi bi-chat-left-text text-secondary fs-5"></i>
                    </div>

                    <div class="p-3 rounded-2 mb-3" style="background-color: var(--linear-surface-2); border-left: 2px solid var(--linear-primary);">
                        <p class="mb-0 fst-italic small text-body">
                            &ldquo;<?= htmlspecialchars($pesan) ?>&rdquo;
                        </p>
                    </div>

                    <div class="d-flex flex-column gap-2 small">
                        <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                            <span class="linear-muted">Kehadiran Kelas:</span>
                            <span class="fw-medium text-body"><?= $kehadiran ?>%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                            <span class="linear-muted">Kelayakan Ujian:</span>
                            <span class="linear-pill <?= $kehadiran >= 75 ? 'linear-pill-success' : 'linear-pill-danger' ?>">
                                <?= $kehadiran >= 75 ? 'Memenuhi Syarat' : 'Belum Memenuhi' ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="linear-muted">Status Remedial:</span>
                            <span class="linear-pill <?= $remedial ? 'linear-pill-danger' : 'linear-pill-success' ?>">
                                <?= $remedial ? 'Wajib Perbaikan' : 'Bebas Remedial' ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 mt-3 border-top linear-muted" style="border-color: var(--linear-hairline) !important; font-size: 11.5px;">
                    <i class="bi bi-info-circle me-1"></i>
                    Untuk konsultasi akademik atau klarifikasi penilaian, silakan hubungi dosen pengampu melalui portal resmi UKRIDA.
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "components/footer.php"; ?>