<?php

/**
 * File: hapus.php
 * Halaman Konfirmasi Hapus Mahasiswa - Linear Design System
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "functions.php";

// Memastikan Pengguna Sudah Login
cekLogin();

$id = $_GET["id"] ?? "";

if ($id === "" || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

$mahasiswa = ambilMahasiswaById($conn, (int) $id);

if ($mahasiswa === null) {
    header("Location: index.php");
    exit;
}

// Eksekusi Penghapusan Data (POST Request)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (hapusMahasiswa($conn, (int) $id)) {
        header("Location: index.php?status=deleted");
        exit;
    } else {
        $error = "Data mahasiswa gagal dihapus dari sistem.";
    }
}

$pageTitle = "Hapus Mahasiswa";
require_once "components/header.php";
?>

<div class="container-xl py-4">
    <!-- Breadcrumb Nav -->
    <div class="d-flex align-items-center gap-2 mb-3 linear-muted small">
        <a href="dashboard.php" class="text-decoration-none linear-muted">
            <i class="bi bi-columns-gap me-1"></i> Dashboard
        </a>
        <span>/</span>
        <a href="index.php" class="text-decoration-none linear-muted">Data Mahasiswa</a>
        <span>/</span>
        <span class="text-body fw-medium">Hapus Mahasiswa</span>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="linear-card p-4 p-md-5">

                <!-- Header Konfirmasi -->
                <div class="text-center mb-4">
                    <div class="linear-modal-icon mx-auto mb-3" style="width: 52px; height: 52px; font-size: 24px; background-color: var(--linear-semantic-danger-subtle); border-color: rgba(235, 87, 87, 0.3); color: var(--linear-semantic-danger);">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h2 class="h3 fw-semibold mb-1">Konfirmasi Penghapusan</h2>
                    <p class="linear-muted small mb-0">Apakah Anda yakin ingin menghapus data mahasiswa berikut dari database?</p>
                </div>

                <!-- Banner Error jika Gagal -->
                <?php if (isset($error)): ?>
                    <div class="linear-alert linear-alert-danger mb-4 p-3 small d-flex align-items-center gap-2">
                        <i class="bi bi-x-circle-fill"></i>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Rincian Identitas Mahasiswa -->
                <div class="p-3 rounded-3 mb-4" style="background-color: var(--linear-surface-2); border: 1px solid var(--linear-hairline);">
                    <div class="d-flex flex-column gap-2 small">
                        <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                            <span class="linear-muted">NIM</span>
                            <span class="fw-semibold text-body"><?= htmlspecialchars($mahasiswa["nim"]) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                            <span class="linear-muted">Nama Lengkap</span>
                            <span class="fw-medium text-body"><?= htmlspecialchars($mahasiswa["nama"]) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                            <span class="linear-muted">Email Resmi</span>
                            <span class="text-body"><?= htmlspecialchars($mahasiswa["email"]) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
                            <span class="linear-muted">Program Studi</span>
                            <span class="linear-pill linear-pill-default"><?= htmlspecialchars($mahasiswa["nama_prodi"]) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="linear-muted">Tahun Masuk</span>
                            <span class="fw-medium text-body"><?= htmlspecialchars($mahasiswa["tahun_masuk"]) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <form method="POST">
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-end gap-2 pt-2 border-top" style="border-color: var(--linear-hairline) !important;">
                        <a href="index.php" class="btn-linear-secondary text-decoration-none w-100 w-sm-auto justify-content-center">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-danger py-2 px-3 fw-medium w-100 w-sm-auto d-inline-flex align-items-center justify-content-center gap-2" style="border-radius: var(--linear-radius-md); font-size: 13px;">
                            <i class="bi bi-trash-fill"></i> Ya, Hapus Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once "components/footer.php"; ?>