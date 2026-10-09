-- Jalankan di phpMyAdmin: pilih database db_iqob -> tab SQL -> paste -> Go.
USE db_iqob;

-- 1) Foto profil tiap user
ALTER TABLE users ADD COLUMN foto VARCHAR(255) NULL AFTER kamar;

-- 2) Pembagian kamar untuk tiap musrif (satu kamar hanya dipegang satu musrif)
CREATE TABLE IF NOT EXISTS musrif_kamar (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  musrif_id BIGINT UNSIGNED NOT NULL,
  kamar TINYINT UNSIGNED NOT NULL,
  UNIQUE KEY uq_musrif_kamar_kamar (kamar),
  KEY idx_musrif_kamar_musrif (musrif_id),
  CONSTRAINT fk_mk_musrif FOREIGN KEY (musrif_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_mk_kamar FOREIGN KEY (kamar) REFERENCES kamars(nomor) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3) Contoh: musrif M001 memegang kamar 1-5 (ubah / hapus sesuai kebutuhan, atau atur lewat Panel Admin)
INSERT IGNORE INTO musrif_kamar (musrif_id, kamar)
SELECT u.id, k.nomor FROM users u JOIN kamars k ON k.nomor BETWEEN 1 AND 5 WHERE u.nim = 'M001' AND u.role = 'musrif';
