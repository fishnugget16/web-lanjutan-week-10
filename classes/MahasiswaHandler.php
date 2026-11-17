<?php
/**
 * File: classes/MahasiswaHandler.php
 * Class OOP untuk menangani logika pendaftaran dan validasi mahasiswa baru
 */

class MahasiswaHandler {
    private $conn;
    private $errors = [];

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function prosesPendaftaran($postData) {
        $nama       = trim($postData["nama"] ?? "");
        $email      = trim($postData["email"] ?? "");
        $password   = trim($postData["password"] ?? "");
        $idProdi    = $postData["id_prodi"] ?? "";
        $tahunMasuk = trim($postData["tahun_masuk"] ?? "");

        // Validasi Input secara OOP
        if ($nama === "") {
            $this->errors[] = "Nama lengkap wajib diisi.";
        }
        if ($email === "") {
            $this->errors[] = "Email wajib diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Format email tidak valid.";
        }
        if ($password === "") {
            $this->errors[] = "Password wajib diisi.";
        }
        if ($idProdi === "") {
            $this->errors[] = "Program studi wajib dipilih.";
        }
        if ($tahunMasuk === "") {
            $this->errors[] = "Tahun masuk wajib diisi.";
        } elseif (!is_numeric($tahunMasuk) || strlen($tahunMasuk) !== 4) {
            $this->errors[] = "Tahun masuk harus berupa 4 digit angka.";
        }

        // Jika lolos validasi, eksekusi penyimpanan
        if (count($this->errors) === 0) {
            $berhasil = tambahMahasiswa(
                $this->conn,
                $nama,
                $email,
                $password,
                (int) $idProdi,
                (int) $tahunMasuk
            );

            if ($berhasil) {
                return true;
            } else {
                $this->errors[] = "Data gagal disimpan. Email mungkin sudah terdaftar.";
            }
        }

        return false;
    }

    public function getErrors() {
        return $this->errors;
    }
}