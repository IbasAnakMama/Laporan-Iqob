-- =====================================================
--  DATABASE SISTEM LAPORAN IQOB (import via phpMyAdmin)
-- =====================================================
CREATE DATABASE IF NOT EXISTS db_iqob CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_iqob;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS iqobs, persyaratan, motivasi, musrif_kamar, users, kamars, mabna;
SET FOREIGN_KEY_CHECKS = 1;

-- Mabna (asrama)
CREATE TABLE mabna (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  UNIQUE KEY uq_mabna_nama (nama)
) ENGINE=InnoDB;

-- Kamar 1 - 46, tiap kamar masuk ke satu mabna
CREATE TABLE kamars (
  nomor TINYINT UNSIGNED NOT NULL PRIMARY KEY,
  mabna_id BIGINT UNSIGNED NULL,
  CONSTRAINT fk_kamars_mabna FOREIGN KEY (mabna_id) REFERENCES mabna(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Satu tabel untuk tiga peran: admin, musrif & santri
CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_lengkap VARCHAR(150) NOT NULL,
  nim VARCHAR(30) NOT NULL,
  role ENUM('admin','musrif','santri') NOT NULL DEFAULT 'santri',
  kamar TINYINT UNSIGNED NULL,
  mabna_id BIGINT UNSIGNED NULL,
  foto VARCHAR(255) NULL,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  UNIQUE KEY uq_users_nim (nim),
  KEY idx_users_role_kamar (role, kamar),
  CONSTRAINT fk_users_kamar FOREIGN KEY (kamar) REFERENCES kamars(nomor) ON DELETE SET NULL,
  CONSTRAINT fk_users_mabna FOREIGN KEY (mabna_id) REFERENCES mabna(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Pembagian kamar tiap musrif
CREATE TABLE musrif_kamar (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  musrif_id BIGINT UNSIGNED NOT NULL,
  kamar TINYINT UNSIGNED NOT NULL,
  UNIQUE KEY uq_musrif_kamar_kamar (kamar),
  KEY idx_musrif_kamar_musrif (musrif_id),
  CONSTRAINT fk_mk_musrif FOREIGN KEY (musrif_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_mk_kamar FOREIGN KEY (kamar) REFERENCES kamars(nomor) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Iqob yang diberikan musrif ke santri
CREATE TABLE iqobs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  santri_id BIGINT UNSIGNED NOT NULL,
  musrif_id BIGINT UNSIGNED NOT NULL,
  kamar TINYINT UNSIGNED NULL,
  tingkat ENUM('ringan','sedang','berat') NOT NULL,
  jenis ENUM('talim_afkar','talim_quran','ubudiyah','soskeg') NOT NULL,
  keterangan TEXT NULL,
  batas_waktu DATETIME NOT NULL,
  surat_pernyataan VARCHAR(255) NULL,
  surat_nama VARCHAR(255) NULL,
  file_pengumpulan VARCHAR(255) NULL,
  file_nama VARCHAR(255) NULL,
  tanggal_dikumpulkan DATETIME NULL,
  status ENUM('belum_dikumpulkan','dikumpulkan','selesai') NOT NULL DEFAULT 'belum_dikumpulkan',
  catatan_musrif TEXT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  KEY idx_iqobs_santri (santri_id),
  KEY idx_iqobs_bulan (created_at),
  CONSTRAINT fk_iqobs_santri FOREIGN KEY (santri_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_iqobs_musrif FOREIGN KEY (musrif_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- PDF persyaratan / aturan iqob
CREATE TABLE persyaratan (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(200) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  file_nama VARCHAR(255) NOT NULL,
  uploaded_by BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_persyaratan_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Kata-kata islami / motivasi (dipilih acak per hari)
CREATE TABLE motivasi (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  teks TEXT NOT NULL,
  sumber VARCHAR(120) NULL
) ENGINE=InnoDB;

-- ================= DATA AWAL =================
INSERT INTO mabna (nama, created_at, updated_at) VALUES
('Mabna Al-Ghazali', NOW(), NOW()),
('Mabna Ibn Rusyd', NOW(), NOW()),
('Mabna Ibn Sina', NOW(), NOW()),
('Mabna Muhasibi', NOW(), NOW()),
('Mabna Ibn Khaldun', NOW(), NOW()),
('Mabna Al-Farabi', NOW(), NOW());

INSERT INTO kamars (nomor) VALUES
(1),(2),(3),(4),(5),(6),(7),(8),(9),(10),(11),(12),(13),(14),(15),(16),(17),(18),(19),(20),(21),(22),(23),
(24),(25),(26),(27),(28),(29),(30),(31),(32),(33),(34),(35),(36),(37),(38),(39),(40),(41),(42),(43),(44),(45),(46);

-- Akun contoh (ganti dengan data asli). Login = nama lengkap + NIM
INSERT INTO users (nama_lengkap, nim, role, kamar, created_at, updated_at) VALUES
('Super Admin', 'ADMIN001', 'admin', NULL, NOW(), NOW()),
('Ahmad Fauzi', 'M001', 'musrif', NULL, NOW(), NOW()),
('Muhammad Rizki', '2024001', 'santri', 1, NOW(), NOW()),
('Abdullah Hakim', '2024002', 'santri', 1, NOW(), NOW()),
('Yusuf Ramadhan', '2024003', 'santri', 2, NOW(), NOW());

UPDATE kamars SET mabna_id = (SELECT id FROM mabna WHERE nama = 'Mabna Al-Ghazali') WHERE nomor BETWEEN 1 AND 5;
UPDATE users SET mabna_id = (SELECT id FROM mabna WHERE nama = 'Mabna Al-Ghazali') WHERE nim = 'M001';

INSERT INTO musrif_kamar (musrif_id, kamar)
SELECT u.id, k.nomor FROM users u JOIN kamars k ON k.nomor BETWEEN 1 AND 5 WHERE u.nim = 'M001';

INSERT INTO motivasi (teks, sumber) VALUES
('Sesungguhnya bersama kesulitan ada kemudahan.', 'QS. Al-Insyirah: 6'),
('Sesungguhnya Allah tidak mengubah keadaan suatu kaum sebelum mereka mengubah keadaan diri mereka sendiri.', 'QS. Ar-Ra''d: 11'),
('Barangsiapa menempuh jalan untuk mencari ilmu, Allah mudahkan baginya jalan menuju surga.', 'HR. Muslim'),
('Sebaik-baik kalian adalah orang yang belajar Al-Qur''an dan mengajarkannya.', 'HR. Bukhari'),
('Sesungguhnya setiap amal tergantung pada niatnya.', 'HR. Bukhari & Muslim'),
('Orang kuat bukanlah yang menang bergulat, tetapi yang mampu menahan dirinya ketika marah.', 'HR. Bukhari & Muslim'),
('Senyummu di hadapan saudaramu adalah sedekah.', 'HR. Tirmidzi'),
('Allah tidak membebani seseorang melainkan sesuai dengan kesanggupannya.', 'QS. Al-Baqarah: 286'),
('Maka ingatlah kepada-Ku, niscaya Aku ingat kepadamu.', 'QS. Al-Baqarah: 152'),
('Sebaik-baik manusia adalah yang paling bermanfaat bagi manusia lainnya.', 'HR. Ahmad'),
('Bersuci itu separuh dari iman.', 'HR. Muslim'),
('Tidak beriman salah seorang di antara kalian hingga ia mencintai untuk saudaranya apa yang ia cintai untuk dirinya sendiri.', 'HR. Bukhari & Muslim'),
('Sesungguhnya Allah beserta orang-orang yang sabar.', 'QS. Al-Baqarah: 153'),
('Barangsiapa tidak merasakan pahitnya belajar sesaat, ia akan merasakan hinanya kebodohan sepanjang hidupnya.', 'Imam Asy-Syafi''i (dinisbatkan)'),
('Setiap anak Adam pasti berbuat salah, dan sebaik-baik orang yang berbuat salah adalah yang bertaubat.', 'HR. Tirmidzi & Ibnu Majah'),
('Iringilah perbuatan buruk dengan perbuatan baik, niscaya kebaikan itu menghapusnya.', 'HR. Tirmidzi'),
('Jadikanlah sabar dan shalat sebagai penolongmu.', 'QS. Al-Baqarah: 45'),
('Dan bahwa manusia hanya memperoleh apa yang telah diusahakannya.', 'QS. An-Najm: 39'),
('Amal yang paling dicintai Allah adalah yang terus-menerus meskipun sedikit.', 'HR. Bukhari & Muslim'),
('Janganlah kamu bersikap lemah dan janganlah bersedih hati, padahal kamulah orang yang paling tinggi derajatnya jika kamu beriman.', 'QS. Ali Imran: 139');
