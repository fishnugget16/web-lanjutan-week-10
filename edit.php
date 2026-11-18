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

$idMahasiswa = $_GET["id"] ?? "";

if ($idMahasiswa === "" || !is_numeric($idMahasiswa)) {
    header("Location: index.php");
    exit;
}

$dataMhs = ambilMahasiswaById($conn, (int) $idMahasiswa);

if ($dataMhs === null) {
    header("Location: index.php");
    exit;
}

$daftarError = [];

$nimSekarang        = $dataMhs["nim"];
$namaLengkap       = $dataMhs["nama"];
$emailMhs      = $dataMhs["email"];
$prodiDipilih    = $dataMhs["id_prodi"];
$angkatan = $dataMhs["tahun_masuk"];

// Ambil semua prodi untuk custom dropdown
$hasilProdi = ambilSemuaProdi($conn);
$daftarProdi = [];
if ($hasilProdi) {
    while ($itemProdi = $hasilProdi->fetch_assoc()) {
        $daftarProdi[] = $itemProdi;
    }
}

// Cari nama prodi terpilih
$labelProdiTerpilih = "Pilih Program Studi";
foreach ($daftarProdi as $itemProdi) {
    if ($itemProdi["id_prodi"] == $prodiDipilih) {
        $labelProdiTerpilih = $itemProdi["nama_prodi"];
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $namaLengkap       = trim($_POST["nama"] ?? "");
    $emailMhs      = trim($_POST["email"] ?? "");
    $prodiDipilih    = $_POST["id_prodi"] ?? "";
    $angkatan = trim($_POST["tahun_masuk"] ?? "");

    // Validasi Input
    if ($namaLengkap === "") {
        $daftarError[] = "Nama lengkap wajib diisi.";
    }

    if ($emailMhs === "") {
        $daftarError[] = "Email wajib diisi.";
    } elseif (!filter_var($emailMhs, FILTER_VALIDATE_EMAIL)) {
        $daftarError[] = "Format email tidak valid.";
    }

    if ($prodiDipilih === "") {
        $daftarError[] = "Program studi wajib dipilih.";
    }

    if ($angkatan === "") {
        $daftarError[] = "Tahun masuk wajib diisi.";
    } elseif (!is_numeric($angkatan) || strlen($angkatan) !== 4) {
        $daftarError[] = "Tahun masuk harus berupa 4 digit angka (misal: 2026).";
    }

    // Update label prodi jika ada error
    foreach ($daftarProdi as $itemProdi) {
        if ($itemProdi["id_prodi"] == $prodiDipilih) {
            $labelProdiTerpilih = $itemProdi["nama_prodi"];
            break;
        }
    }

    // Eksekusi jika validasi lolos
    if (count($daftarError) === 0) {
        $sukses = updateMahasiswa(
            $conn,
            (int) $idMahasiswa,
            $namaLengkap,
            $emailMhs,
            (int) $prodiDipilih,
            (int) $angkatan
        );

        if ($sukses) {
            header("Location: index.php?status=updated");
            exit;
        } else {
            $daftarError[] = "Data gagal diperbarui. Email mungkin sudah terdaftar.";
        }
    }
}

$pageTitle = "Edit Mahasiswa";
require_once "components/header.php";
?>

<div class="container-xl py-4">
    <!-- Breadcrumb Nav -->
    <div class="d-flex align-items-center gap-2 mb-3 teks-redup small">
        <a href="dashboard.php" class="text-decoration-none teks-redup">
            <i class="bi bi-columns-gap me-1"></i> Dashboard
        </a>
        <span>/</span>
        <a href="index.php" class="text-decoration-none teks-redup">Data Mahasiswa</a>
        <span>/</span>
        <span class="text-body fw-medium">Edit Mahasiswa</span>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="kartu-form p-4 p-md-5">

                <!-- Header Panel -->
                <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom" style="border-color: var(--linear-hairline) !important;">
                    <div>
                        <div class="label-atas mb-1">Pembaruan Data &bull; Auto Regenerate NIM</div>
                        <h2 class="h3 fw-semibold mb-0">Edit Data Mahasiswa</h2>
                        <p class="teks-redup small mb-0">NIM akan otomatis terhitung ulang jika Program Studi atau Tahun Masuk diubah</p>
                    </div>
                    <a href="index.php" class="tombol-ghost text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <!-- Banner Error -->
                <?php if (count($daftarError) > 0): ?>
                    <div class="kotak-notif kotak-notif-error d-flex flex-column gap-1 mb-4 p-3 small">
                        <div class="fw-semibold d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill"></i> Terjadi Kesalahan Input:
                        </div>
                        <ul class="mb-0 ps-4">
                            <?php foreach ($daftarError as $pesanError): ?>
                                <li><?= htmlspecialchars($pesanError) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Form Inputs -->
                <form method="POST" action="edit.php?id=<?= $idMahasiswa ?>">
                    <!-- Informasional NIM Saat Ini -->
                    <div class="mb-3">
                        <label class="form-label small fw-medium mb-1">NIM Saat Ini (Auto Generated)</label>
                        <input 
                            type="text" 
                            class="form-control text-secondary" 
                            value="<?= htmlspecialchars($nimSekarang) ?>" 
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
                            value="<?= htmlspecialchars($namaLengkap) ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-medium mb-1">Email Resmi</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($emailMhs) ?>"
                            required>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Custom Dropdown Program Studi -->
                        <div class="col-12 col-md-7">
                            <label class="form-label small fw-medium mb-1">Program Studi</label>
                            
                            <!-- Hidden input nilai id_prodi -->
                            <input type="hidden" name="id_prodi" id="inputProdi" value="<?= htmlspecialchars($prodiDipilih) ?>" required>

                            <div class="dropdown w-100">
                                <button class="form-control d-flex align-items-center justify-content-between w-100 text-start" 
                                        type="button" 
                                        id="tombolDropdownProdi" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false"
                                        style="cursor: pointer; min-height: 38px;">
                                    <span class="text-truncate" id="labelProdiTerpilih">
                                        <?= htmlspecialchars($labelProdiTerpilih) ?>
                                    </span>
                                    <i class="bi bi-chevron-down ms-2 small teks-redup"></i>
                                </button>

                                <ul class="dropdown-menu menu-dropdown-kustom w-100 mt-2" aria-labelledby="tombolDropdownProdi">
                                    <?php foreach ($daftarProdi as $itemProdi): ?>
                                        <li>
                                            <a class="item-dropdown-kustom pilihan-prodi <?= $prodiDipilih == $itemProdi["id_prodi"] ? "active" : "" ?>" 
                                               href="#" 
                                               data-value="<?= $itemProdi["id_prodi"] ?>" 
                                               data-text="<?= htmlspecialchars($itemProdi["nama_prodi"]) ?>">
                                                <i class="bi bi-book text-secondary"></i>
                                                <?= htmlspecialchars($itemProdi["nama_prodi"]) ?>
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
                                value="<?= htmlspecialchars($angkatan) ?>"
                                required>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top" style="border-color: var(--linear-hairline) !important;">
                        <a href="index.php" class="tombol-sekunder text-decoration-none">Batal</a>
                        <button type="submit" class="tombol-utama">
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
    const daftarPilihanProdi = document.querySelectorAll(".pilihan-prodi");
    const inputProdi = document.getElementById("inputProdi");
    const labelProdi = document.getElementById("labelProdiTerpilih");

    daftarPilihanProdi.forEach(pilihan => {
        pilihan.addEventListener("click", function (e) {
            e.preventDefault();
            const nilaiProdi = this.getAttribute("data-value");
            const teksProdi = this.getAttribute("data-text");

            inputProdi.value = nilaiProdi;
            labelProdi.textContent = teksProdi;

            daftarPilihanProdi.forEach(el => el.classList.remove("active"));
            this.classList.add("active");
        });
    });
});
</script>

<?php require_once "components/footer.php"; ?>