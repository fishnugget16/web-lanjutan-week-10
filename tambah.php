<?php

/**
 * File: tambah.php
 * Halaman Form Tambah Mahasiswa Baru - Linear Design System & OOP MahasiswaHandler
 * Pure Sans-Serif (Inter), Fully Responsive
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "functions.php";
require_once "classes/MahasiswaHandler.php"; // Panggil class OOP

$nama = "";
$email = "";
$password = "";
$idProdi = "";
$tahunMasuk = date("Y"); // Default ke tahun berjalan

$errors = [];

$resultProdi = ambilSemuaProdi($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Menggunakan Class OOP MahasiswaHandler
    $handler = new MahasiswaHandler($conn);
    $berhasil = $handler->prosesPendaftaran($_POST);

    if ($berhasil) {
        header("Location: index.php");
        exit;
    } else {
        $errors = $handler->getErrors();
        // Ambil kembali input user agar tidak hilang di form saat terjadi error
        $nama       = trim($_POST["nama"] ?? "");
        $email      = trim($_POST["email"] ?? "");
        $idProdi    = $_POST["id_prodi"] ?? "";
        $tahunMasuk = trim($_POST["tahun_masuk"] ?? "");
    }
}

$pageTitle = "Tambah Mahasiswa";
require_once "components/header.php";
?>

<div class="container-xl py-4">
    <!-- Breadcrumb Nav -->
    <div class="d-flex align-items-center gap-2 mb-3 linear-muted small">
        <a href="index.php" class="text-decoration-none linear-muted">
            <i class="bi bi-people me-1"></i> Data Mahasiswa
        </a>
        <span>/</span>
        <span class="text-body fw-medium">Tambah Mahasiswa</span>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="linear-card p-4 p-md-5">

                <!-- Header Panel -->
                <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom" style="border-color: var(--linear-hairline) !important;">
                    <div>
                        <div class="linear-eyebrow mb-1">Formulir Pendaftaran &bull; Automated NIM Generator &amp; OOP</div>
                        <h2 class="h3 fw-semibold mb-0">Tambah Mahasiswa Baru</h2>
                        <p class="linear-muted small mb-0">NIM akan di-generate otomatis oleh sistem setelah disimpan</p>
                    </div>
                    <a href="index.php" class="btn-linear-ghost text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <!-- Banner Error -->
                <?php if (count($errors) > 0): ?>
                    <div class="linear-alert linear-alert-danger d-flex flex-column gap-1 mb-4 p-3 small">
                        <div class="fw-semibold d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill"></i> Terjadi Kesalahan Input:
                        </div>
                        <ul class="mb-0 ps-4">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Form Inputs -->
                <form method="POST" action="tambah.php">
                    <div class="mb-3">
                        <label for="nama" class="form-label small fw-medium mb-1">Nama Lengkap</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nama"
                            name="nama"
                            placeholder="Contoh: Andi Wijaya"
                            value="<?= htmlspecialchars($nama) ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-medium mb-1">Email Resmi</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            placeholder="nama@domain.ac.id"
                            value="<?= htmlspecialchars($email) ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-medium mb-1">Kata Sandi</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Masukkan kata sandi baru"
                            required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-7">
                            <label for="id_prodi" class="form-label small fw-medium mb-1">Program Studi</label>
                            <select class="form-select" id="id_prodi" name="id_prodi" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <?php if ($resultProdi): ?>
                                    <?php while ($prodi = $resultProdi->fetch_assoc()): ?>
                                        <option value="<?= $prodi["id_prodi"] ?>" <?= $idProdi == $prodi["id_prodi"] ? "selected" : "" ?>>
                                            <?= htmlspecialchars($prodi["nama_prodi"]) ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-12 col-md-5">
                            <label for="tahun_masuk" class="form-label small fw-medium mb-1">Tahun Masuk</label>
                            <input
                                type="number"
                                class="form-control"
                                id="tahun_masuk"
                                name="tahun_masuk"
                                min="2000"
                                max="2099"
                                placeholder="2026"
                                value="<?= htmlspecialchars($tahunMasuk) ?>"
                                required>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top" style="border-color: var(--linear-hairline) !important;">
                        <a href="index.php" class="btn-linear-secondary text-decoration-none">Batal</a>
                        <button type="submit" class="btn-linear-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan &amp; Tambah Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once "components/footer.php"; ?>