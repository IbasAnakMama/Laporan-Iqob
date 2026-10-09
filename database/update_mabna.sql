-- Jalankan di phpMyAdmin: pilih database db_iqob -> tab SQL -> paste -> Go.
USE db_iqob;

-- 1) Tabel mabna (asrama)
CREATE TABLE IF NOT EXISTS mabna (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  UNIQUE KEY uq_mabna_nama (nama)
) ENGINE=InnoDB;

INSERT IGNORE INTO mabna (nama, created_at, updated_at) VALUES
('Mabna Al-Ghazali', NOW(), NOW()),
('Mabna Ibn Rusyd', NOW(), NOW()),
('Mabna Ibn Sina', NOW(), NOW()),
('Mabna Muhasibi', NOW(), NOW()),
('Mabna Ibn Khaldun', NOW(), NOW()),
('Mabna Al-Farabi', NOW(), NOW());

-- 2) Tiap kamar masuk ke satu mabna (diatur super admin)
ALTER TABLE kamars
  ADD COLUMN mabna_id BIGINT UNSIGNED NULL,
  ADD CONSTRAINT fk_kamars_mabna FOREIGN KEY (mabna_id) REFERENCES mabna(id) ON DELETE SET NULL;

-- 3) Musrif tergabung di satu mabna
ALTER TABLE users
  ADD COLUMN mabna_id BIGINT UNSIGNED NULL AFTER kamar,
  ADD CONSTRAINT fk_users_mabna FOREIGN KEY (mabna_id) REFERENCES mabna(id) ON DELETE SET NULL;

-- 4) Data contoh: kamar 1-5 = Mabna Al-Ghazali, musrif M001 di mabna itu.
--    Kamar 6-46 dibagi sendiri lewat menu Kelola Mabna di panel super admin.
UPDATE kamars SET mabna_id = (SELECT id FROM mabna WHERE nama = 'Mabna Al-Ghazali') WHERE nomor BETWEEN 1 AND 5;
UPDATE users SET mabna_id = (SELECT id FROM mabna WHERE nama = 'Mabna Al-Ghazali') WHERE nim = 'M001' AND role = 'musrif';
