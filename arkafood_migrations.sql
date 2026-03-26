-- Migrations for ArkaFood: add columns and tables used by admin
USE `arkafood`;

-- add is_visible to products if not exists
ALTER TABLE products
  ADD COLUMN IF NOT EXISTS is_visible TINYINT(1) DEFAULT 1;

-- settings table for about content (only create if not exists)
CREATE TABLE IF NOT EXISTS settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT
);

-- directors table
CREATE TABLE IF NOT EXISTS directors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  title VARCHAR(255),
  image VARCHAR(255),
  ord INT DEFAULT 0
);
