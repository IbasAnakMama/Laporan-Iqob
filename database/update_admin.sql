-- Jalankan di phpMyAdmin (pilih database db_iqob -> tab SQL) jika database sudah terlanjur di-import.
USE db_iqob;
ALTER TABLE users MODIFY role ENUM('admin','musrif','santri') NOT NULL DEFAULT 'santri';
INSERT INTO users (nama_lengkap, nim, role, kamar, created_at, updated_at)
VALUES ('Super Admin', 'ADMIN001', 'admin', NULL, NOW(), NOW());
