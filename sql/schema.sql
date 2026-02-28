CREATE DATABASE IF NOT EXISTS rahveer_db;
USE rahveer_db;

CREATE TABLE IF NOT EXISTS vendors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  shop_name VARCHAR(200) NOT NULL,
  mobile VARCHAR(10) NOT NULL,
  city VARCHAR(100) NOT NULL,
  service_type VARCHAR(50) NOT NULL,
  document_path VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_mobile (mobile),
  INDEX idx_created (created_at),
  INDEX idx_service (service_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admins (username, password_hash) VALUES ('rahveer_admin', '$2y$10$G/2c3v9/8SU1U3g1msDI..BWQmlMe1m0LWMN1JWCgyGXyPXcaPn0.')
ON DUPLICATE KEY UPDATE username = username;
