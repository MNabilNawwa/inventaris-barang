-- ============================================================
-- Database: Sistem Informasi Inventaris Barang
-- Import lewat phpMyAdmin (tab Import) atau:
--   mysql -u root -p < database.sql
-- ============================================================
CREATE DATABASE IF NOT EXISTS inventaris_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_db;

DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username   VARCHAR(50)  NOT NULL,
  password   VARCHAR(255) NOT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE barang (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  kode_barang VARCHAR(20)  NOT NULL,
  nama_barang VARCHAR(100) NOT NULL,
  kategori    VARCHAR(30)  NOT NULL,
  jumlah      INT UNSIGNED NOT NULL DEFAULT 0,
  harga       INT UNSIGNED NOT NULL DEFAULT 0,
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_barang_kode (kode_barang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Akun default: username = admin | password = admin123
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$L6QgZimJvEqolF4NGjPuTufLfaOQk7NvfyTk3UgkHZE6fwQc5vbTy');

-- Data contoh
INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga) VALUES
('ELK-001', 'Laptop Asus Vivobook 14',  'Elektronik', 8,  7500000),
('ELK-002', 'Mouse Wireless Logitech',  'Elektronik', 25, 150000),
('ATK-001', 'Pulpen Standard AE7',      'Alat Tulis', 120, 3500),
('ATK-002', 'Kertas HVS A4 70gr (rim)', 'Alat Tulis', 40, 52000),
('FRN-001', 'Kursi Kantor Ergonomis',   'Furnitur',   6,  850000),
('PRK-001', 'Obeng Set 12 in 1',        'Perkakas',   0,  65000);
