<?php
/**
 * File: Mahasiswa.php
 * Implementasi Object-Oriented Programming (OOP) untuk Entitas Mahasiswa
 * Terintegrasi dengan Sistem Database MySQL Student Portal UKRIDA
 */

class Mahasiswa {
    // Properties (Encapsulation)
    private $conn;
    private $id;
    private $nim;
    private $nama;
    private $email;
    private $idProdi;
    private $tahunMasuk;

    // Constructor untuk inisialisasi koneksi database
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Setter untuk mengisi data mahasiswa
    public function setData(
        $id,
        $nim,
        $nama,
        $email,
        $idProdi,
        $tahunMasuk
    ) {
        $this->id         = $id;
        $this->nim        = $nim;
        $this->nama       = $nama;
        $this->email      = $email;
        $this->idProdi    = $idProdi;
        $this->tahunMasuk = $tahunMasuk;
    }

    // Getter untuk mengambil data NIM
    public function getNim() {
        return $this->nim;
    }

    // Getter untuk mengambil Nama
    public function getNama() {
        return $this->nama;
    }

    // Getter untuk Email
    public function getEmail() {
        return $this->email;
    }

    // Method OOP: Mengambil seluruh data mahasiswa berelasi dengan program studi
    public function getAllMahasiswa() {
        $sql = "SELECT 
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

        return $this->conn->query($sql);
    }

    // Method OOP: Validasi sederhana format email kampus
    public function validateEmailDomain() {
        return str_ends_with($this->email, "@ukrida.ac.id") || filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    // Method OOP: Menampilkan informasi ringkas objek mahasiswa
    public function getInfoObjek() {
        return "[OOP Entity] Mahasiswa: " . htmlspecialchars($this->nama) . " (" . htmlspecialchars($this->nim) . ")";
    }
}
?>