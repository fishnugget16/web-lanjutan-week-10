<?php

/**
 * File: functions.php
 * Kumpulan Fungsi Helper, Otentikasi, dan Akses Database
 * Integrated with PHP-driven NIM Auto-Generation & Linear Design System
 */

// 1. Fungsi Proteksi Sesi Login
function cekLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["isLogin"]) || $_SESSION["isLogin"] !== true) {
        header("Location: login.php");
        exit;
    }
}

// 2. Fungsi Mengambil Semua Data Mahasiswa
function ambilSemuaMahasiswa($conn) {
    $sql = 
        "SELECT
            mahasiswa.id,
            mahasiswa.nim,
            mahasiswa.nama,
            mahasiswa.email,
            program_studi.nama_prodi,
            mahasiswa.tahun_masuk
        FROM mahasiswa
        JOIN program_studi
            ON mahasiswa.id_prodi = program_studi.id_prodi
        ORDER BY mahasiswa.id DESC";

    return $conn->query($sql);
}

// 3. Fungsi Mengambil Data Program Studi
function ambilSemuaProdi($conn) {
    $sql = 
        "SELECT *
        FROM program_studi
        ORDER BY nama_prodi ASC";

    return $conn->query($sql);
}

// 4. Fungsi Tambah Mahasiswa
function tambahMahasiswa(
    $conn,
    $nama,
    $email,
    $password,
    $idProdi,
    $tahunMasuk
) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO mahasiswa (
            nama,
            email,
            password,
            id_prodi,
            tahun_masuk
        )
        VALUES (?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        "sssii",
        $nama,
        $email,
        $hashedPassword,
        $idProdi,
        $tahunMasuk
    );

    return $stmt->execute();
}

// 5. Helper UI: Mendapatkan Inisial Nama untuk Avatar Header
function getInitials($nama) {
    $words = explode(" ", trim($nama));
    $initials = "";
    foreach ($words as $w) {
        if (!empty($w)) {
            $initials .= strtoupper($w[0]);
        }
    }
    return substr($initials, 0, 2);
}

// 6. Helper UI: Membaca Preferensi Tema Tampilan (Dark / Light)
function getTheme() {
    return $_COOKIE['theme'] ?? 'dark';
}

// 7. Fungsi Otentikasi Login berbasis Database MySQL
function loginMahasiswa($conn, $email, $password) {
    $stmt = $conn->prepare(
        "SELECT 
            mahasiswa.id,
            mahasiswa.nim,
            mahasiswa.nama,
            mahasiswa.email,
            mahasiswa.password,
            program_studi.nama_prodi
        FROM mahasiswa
        JOIN program_studi ON mahasiswa.id_prodi = program_studi.id_prodi
        WHERE mahasiswa.email = ? 
        LIMIT 1"
    );

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            return $row;
        }
    }

    return null;
}

// 8. Ambil Data Detail 1 Mahasiswa Berdasarkan NIM
function ambilMahasiswaByNim($conn, $nim) {
    $stmt = $conn->prepare(
        "SELECT 
            mahasiswa.*, 
            program_studi.nama_prodi 
        FROM mahasiswa 
        JOIN program_studi ON mahasiswa.id_prodi = program_studi.id_prodi 
        WHERE mahasiswa.nim = ? 
        LIMIT 1"
    );

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("s", $nim);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// 9. Ambil Data Detail 1 Mahasiswa Berdasarkan ID
function ambilMahasiswaById($conn, $id) {
    $stmt = $conn->prepare(
        "SELECT 
            mahasiswa.*,
            program_studi.nama_prodi
        FROM mahasiswa
        JOIN program_studi
            ON mahasiswa.id_prodi = program_studi.id_prodi
        WHERE mahasiswa.id = ?
        LIMIT 1"
    );

    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

// 10. Update Data Mahasiswa (Regenerate NIM Otomatis via PHP jika Prodi / Tahun Masuk Berubah)
function updateMahasiswa($conn, $id, $nama, $email, $idProdi, $tahunMasuk) {
    // Ambil data lama mahasiswa
    $stmtOld = $conn->prepare("SELECT id_prodi, tahun_masuk, nim FROM mahasiswa WHERE id = ? LIMIT 1");
    if (!$stmtOld) return false;
    
    $stmtOld->bind_param("i", $id);
    $stmtOld->execute();
    $oldData = $stmtOld->get_result()->fetch_assoc();
    $stmtOld->close();

    if (!$oldData) return false;

    $nimBaru = $oldData["nim"]; // Default memakai NIM yang ada

    // Jika id_prodi atau tahun_masuk berubah, hitung ulang NIM baru
    if ($oldData["id_prodi"] != $idProdi || $oldData["tahun_masuk"] != $tahunMasuk) {
        
        // Ambil kode prodi baru
        $stmtProdi = $conn->prepare("SELECT kode_prodi FROM program_studi WHERE id_prodi = ? LIMIT 1");
        if (!$stmtProdi) return false;
        
        $stmtProdi->bind_param("i", $idProdi);
        $stmtProdi->execute();
        $resProdi = $stmtProdi->get_result()->fetch_assoc();
        $stmtProdi->close();

        if (!$resProdi) return false;
        $kodeProdi = $resProdi["kode_prodi"];

        // Hitung jumlah mahasiswa di prodi & tahun baru (kecuali ID yang sedang diedit)
        $stmtCount = $conn->prepare(
            "SELECT COUNT(*) as total 
             FROM mahasiswa 
             WHERE id_prodi = ? AND tahun_masuk = ? AND id != ?"
        );
        if (!$stmtCount) return false;
        
        $stmtCount->bind_param("iii", $idProdi, $tahunMasuk, $id);
        $stmtCount->execute();
        $resCount = $stmtCount->get_result()->fetch_assoc();
        $stmtCount->close();

        $nextUrut = ((int) $resCount["total"]) + 1;

        // Formulasi NIM baru: [KodeProdi][TahunMasuk][3 Digit Urutan]
        $nimBaru = $kodeProdi . $tahunMasuk . str_pad($nextUrut, 3, "0", STR_PAD_LEFT);
    }

    // Eksekusi UPDATE dengan NIM baru yang telah dihitung
    $stmtUpdate = $conn->prepare(
        "UPDATE mahasiswa 
         SET nim = ?, 
             nama = ?, 
             email = ?, 
             id_prodi = ?, 
             tahun_masuk = ? 
         WHERE id = ?"
    );

    if (!$stmtUpdate) return false;

    $stmtUpdate->bind_param("sssiii", $nimBaru, $nama, $email, $idProdi, $tahunMasuk, $id);
    return $stmtUpdate->execute();
}

// 11. Hapus Data Mahasiswa Berdasarkan ID
function hapusMahasiswa($conn, $id) {
    $stmt = $conn->prepare(
        "DELETE FROM mahasiswa WHERE id = ?"
    );
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// 12. Helper Perhitungan Akademik
function hitungNilaiAkhir($tugas, $uts, $uas) {
    return ($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40);
}

function tentukanGrade($nilai) {
    if ($nilai >= 85) return 'A';
    if ($nilai >= 75) return 'B';
    if ($nilai >= 65) return 'C';
    if ($nilai >= 50) return 'D';
    return 'E';
}

function tentukanStatusKelulusan($nilai, $kehadiran) {
    return ($nilai >= 60 && $kehadiran >= 75) ? 'LULUS' : 'TIDAK LULUS';
}

function tentukanPredikat($nilai, $kehadiran) {
    if ($nilai >= 85 && $kehadiran >= 85) return 'Dengan Pujian (Cum Laude)';
    if ($nilai >= 75) return 'Sangat Memuaskan';
    if ($nilai >= 60) return 'Memuaskan';
    return 'Cukup';
}

function buatPesan($grade) {
    switch ($grade) {
        case 'A': return 'Pertahankan prestasi akademik Anda yang sangat baik!';
        case 'B': return 'Hasil studi bagus, tingkatkan lagi untuk meraih nilai maksimal.';
        case 'C': return 'Cukup baik, pertimbangkan untuk meningkatkan porsi belajar.';
        default: return 'Perlu evaluasi belajar dan berkonsultasi dengan dosen pembimbing.';
    }
}

function perluRemedial($nilai) {
    return $nilai < 60;
}

// 13. Helper Class Pill Tampilan UI
function getGradeLabelClass($grade) {
    return ($grade === 'A' || $grade === 'B') ? 'linear-pill-success' : 'linear-pill-danger';
}

function getStatusLabelClass($status) {
    return $status === 'LULUS' ? 'linear-pill-success' : 'linear-pill-danger';
}

function getPredikatLabelClass($predikat) {
    return str_contains($predikat, 'Cum Laude') ? 'linear-pill-primary' : 'linear-pill-default';
}