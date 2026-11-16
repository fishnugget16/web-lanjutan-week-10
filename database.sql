CREATE DATABASE IF NOT EXISTS db_mahasiswa;
USE db_mahasiswa;

-- 1. TABEL PROGRAM STUDI
CREATE TABLE program_studi (
    id_prodi INT AUTO_INCREMENT PRIMARY KEY,
    kode_prodi VARCHAR(2) NOT NULL UNIQUE,
    nama_prodi VARCHAR(100) NOT NULL UNIQUE
);

-- 2. TABEL MAHASISWA (Dengan kolom tahun_masuk)
CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_prodi INT NOT NULL,
    tahun_masuk YEAR NOT NULL, -- Kolom tahun masuk eksplisit
    nilai_tugas DECIMAL(5,2) DEFAULT 0,
    nilai_uts DECIMAL(5,2) DEFAULT 0,
    nilai_uas DECIMAL(5,2) DEFAULT 0,
    kehadiran INT DEFAULT 0,
    FOREIGN KEY (id_prodi) REFERENCES program_studi(id_prodi) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_prodi_tahun (id_prodi, tahun_masuk)
);

-- 3. TRIGGER GENERATE NIM OTOMATIS
DELIMITER //

CREATE TRIGGER before_insert_mahasiswa
BEFORE INSERT ON mahasiswa
FOR EACH ROW
BEGIN
    DECLARE v_kode_prodi VARCHAR(2);
    DECLARE v_count INT;
    DECLARE v_next_urut INT;

    -- Ambil kode_prodi berdasarkan id_prodi yang dipilih
    SELECT kode_prodi INTO v_kode_prodi 
    FROM program_studi 
    WHERE id_prodi = NEW.id_prodi;

    -- Hitung jumlah mahasiswa di prodi & tahun masuk yang sama
    SELECT COUNT(*) INTO v_count 
    FROM mahasiswa 
    WHERE id_prodi = NEW.id_prodi 
      AND tahun_masuk = NEW.tahun_masuk;

    SET v_next_urut = v_count + 1;

    -- Format NIM: [KodeProdi][TahunMasuk][3 Digit Urutan] (misal: 422026001)
    SET NEW.nim = CONCAT(
        v_kode_prodi, 
        NEW.tahun_masuk, 
        LPAD(v_next_urut, 3, '0')
    );
END//

DELIMITER ;