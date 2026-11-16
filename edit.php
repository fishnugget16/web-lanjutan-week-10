<?php

/**
 * File: edit.php
 * Halaman Edit Mahasiswa - Linear Design System
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

$errors = [];

$nim        = $mahasiswa["nim"];
$nama       = $mahasiswa["nama"];
$email      = $mahasiswa["email"];
$idProdi    = $mahasiswa["id_prodi"];
$tahunMasuk = $mahasiswa["tahun_masuk"];

// Ambil semua prodi untuk custom dropdown
$resultProdi = ambilSemuaProdi($conn);
$prodiList = [];
if ($resultProdi) {
    while ($p = $resultProdi->fetch_assoc()) {
        $prodiList[] = $p;
    }
}

// Cari nama prodi terpilih
$selectedProdiText = "Pilih Program Studi";
foreach ($prodiList as $p) {
    if ($p["id_prodi"] == $idProdi) {
        $selectedProdiText = $p["nama_prodi"];
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama       = trim($_POST["nama"] ?? "");
    $email      = trim($_POST["email"] ?? "");
    $idProdi    = $_POST["id_prodi"] ?? "";
    $tahunMasuk = trim($_POST["tahun_masuk"] ?? "");

    // Validasi Input
    if ($nama === "") {
        $errors[] = "Nama lengkap wajib diisi.";
    }

    if ($email === "") {
        $errors[] = "Email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    if ($idProdi === "") {
        $errors[] = "Program studi wajib dipilih.";
    }

    if ($tahunMasuk === "") {
        $errors[] = "Tahun masuk wajib diisi.";
    } elseif (!is_numeric($tahunMasuk) || strlen($tahunMasuk) !== 4) {
        $errors[] = "Tahun masuk harus berupa 4 digit angka (misal: 2026).";
    }

    // Update label prodi jika ada error
    foreach ($prodiList as $p) {
        if ($p["id_prodi"] == $idProdi) {
            $selectedProdiText = $p["nama_prodi"];
            break;
        }
    }

    // Eksekusi jika validasi lolos
    if (count($errors) === 0) {
        $berhasil = updateMahasiswa(
            $conn,
            (int) $id,
            $nama,
            $email,
            (int) $idProdi,
            (int) $tahunMasuk
        );

        if ($berhasil) {
            header("Location: index.php?status=updated");
            exit;
        } else {
            $errors[] = "Data gagal diperbarui. Email mungkin sudah terdaftar.";
        }
    }
}

$pageTitle = "Edit Mahasiswa";
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
        <span class="text-body fw-medium">Edit Mahasiswa</span>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="linear-card p-4 p-md-5">

                <!-- Header Panel -->
                <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom" style="border-color: var(--linear-hairline) !important;">
                    <div>
                        <div class="linear-eyebrow mb-1">Pembaruan Data &bull; Auto Regenerate NIM</div>
                        <h2 class="h3 fw-semibold mb-0">Edit Data Mahasiswa</h2>
                        <p class="linear-muted small mb-0">NIM akan otomatis terhitung ulang jika Program Studi atau Tahun Masuk diubah</p>
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
                <form method="POST" action="edit.php?id=<?= $id ?>">
                    <!-- Informasional NIM Saat Ini -->
                    <div class="mb-3">
                        <label class="form-label small fw-medium mb-1">NIM Saat Ini (Auto Generated)</label>
                        <input 
                            type="text" 
                            class="form-control text-secondary" 
                            value="<?= htmlspecialchars($nim) ?>" 
                            disabled 
                            readonly 
                            style="background-color: var(--linear-surface-2);">
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label small fw-medium mb-1">Nama Lengkap</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nama"
                            name="nama"
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
                            value="<?= htmlspecialchars($email) ?>"
                            required>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Custom Dropdown Program Studi -->
                        <div class="col-12 col-md-7">
                            <label class="form-label small fw-medium mb-1">Program Studi</label>
                            
                            <!-- Hidden input nilai id_prodi -->
                            <input type="hidden" name="id_prodi" id="id_prodi" value="<?= htmlspecialchars($idProdi) ?>" required>

                            <div class="dropdown w-100">
                                <button class="form-control d-flex align-items-center justify-content-between w-100 text-start" 
                                        type="button" 
                                        id="dropdownProdiBtn" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false"
                                        style="cursor: pointer; min-height: 38px;">
                                    <span class="text-truncate" id="selectedProdiLabel">
                                        <?= htmlspecialchars($selectedProdiText) ?>
                                    </span>
                                    <i class="bi bi-chevron-down ms-2 small linear-muted"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-linear w-100 mt-2" aria-labelledby="dropdownProdiBtn">
                                    <?php foreach ($prodiList as $prodi): ?>
                                        <li>
                                            <a class="dropdown-item-linear custom-prodi-item <?= $idProdi == $prodi["id_prodi"] ? "active" : "" ?>" 
                                               href="#" 
                                               data-value="<?= $prodi["id_prodi"] ?>" 
                                               data-text="<?= htmlspecialchars($prodi["nama_prodi"]) ?>">
                                                <i class="bi bi-book text-secondary"></i>
                                                <?= htmlspecialchars($prodi["nama_prodi"]) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Edit Tahun Masuk -->
                        <div class="col-12 col-md-5">
                            <label for="tahun_masuk" class="form-label small fw-medium mb-1">Tahun Masuk (Angkatan)</label>
                            <input
                                type="number"
                                class="form-control"
                                id="tahun_masuk"
                                name="tahun_masuk"
                                min="2000"
                                max="2099"
                                value="<?= htmlspecialchars($tahunMasuk) ?>"
                                required>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top" style="border-color: var(--linear-hairline) !important;">
                        <a href="index.php" class="btn-linear-secondary text-decoration-none">Batal</a>
                        <button type="submit" class="btn-linear-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const prodiItems = document.querySelectorAll(".custom-prodi-item");
    const hiddenInput = document.getElementById("id_prodi");
    const labelSpan = document.getElementById("selectedProdiLabel");

    prodiItems.forEach(item => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            const value = this.getAttribute("data-value");
            const text = this.getAttribute("data-text");

            hiddenInput.value = value;
            labelSpan.textContent = text;

            prodiItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");
        });
    });
});
</script>

<?php require_once "components/footer.php"; ?>