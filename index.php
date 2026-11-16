<?php

/**
 * File: index.php
 * Halaman Utama - Daftar Mahasiswa (Linear Design System)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";
require_once "functions.php";

// Memastikan Pengguna Sudah Login
cekLogin();

$result = ambilSemuaMahasiswa($conn);

$pageTitle = "Daftar Mahasiswa";
require_once "components/header.php";
?>

<div class="container-xl py-4">
    <!-- Breadcrumb Nav -->
    <div class="d-flex align-items-center gap-2 mb-3 linear-muted small">
        <a href="dashboard.php" class="text-decoration-none linear-muted">
            <i class="bi bi-columns-gap me-1"></i> Dashboard
        </a>
        <span>/</span>
        <span class="text-body fw-medium">Data Mahasiswa</span>
    </div>

    <!-- Workspace Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom" style="border-color: var(--linear-hairline) !important;">
        <div>
            <div class="linear-eyebrow mb-1">
                <span class="linear-status-dot linear-status-dot-success"></span> Data Mahasiswa &bull; System Active
            </div>
            <h1 class="h2 fw-semibold mb-1">Daftar Mahasiswa</h1>
            <p class="linear-muted small mb-0">Kelola informasi data mahasiswa dan pembuatan NIM otomatis</p>
        </div>
        <div>
            <a href="tambah.php" class="btn-linear-primary d-inline-flex align-items-center gap-2 text-decoration-none">
                <i class="bi bi-plus-lg"></i> Tambah Mahasiswa Baru
            </a>
        </div>
    </div>

    <!-- Main Table Panel -->
    <div class="linear-card">
        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--linear-hairline) !important;">
            <span class="linear-eyebrow">Direktori Akademik</span>
            <span class="linear-pill linear-pill-default">TOTAL DATA: <?= $result ? $result->num_rows : 0 ?></span>
        </div>

        <div class="table-responsive">
            <table class="linear-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Program Studi</th>
                        <th class="text-center">Tahun Masuk</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center linear-muted"><?= $no++; ?></td>
                                <td>
                                    <span class="fw-semibold text-body"><?= htmlspecialchars($row["nim"]); ?></span>
                                </td>
                                <td>
                                    <div class="fw-medium text-body"><?= htmlspecialchars($row["nama"]); ?></div>
                                </td>
                                <td class="linear-muted"><?= htmlspecialchars($row["email"]); ?></td>
                                <td>
                                    <span class="linear-pill linear-pill-default"><?= htmlspecialchars($row["nama_prodi"]); ?></span>
                                </td>
                                <td class="text-center fw-medium text-body"><?= htmlspecialchars($row["tahun_masuk"]); ?></td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-secondary py-1 px-2 text-decoration-none" title="Edit Data">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger py-1 px-2 text-decoration-none" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');" title="Hapus Data">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 linear-muted">
                                <i class="bi bi-inbox fs-4 d-block mb-2 text-secondary"></i>
                                Belum ada data mahasiswa terdaftar.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once "components/footer.php"; ?>