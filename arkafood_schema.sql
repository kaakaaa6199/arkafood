-- ArkaFood MySQL schema + minimal seed data
-- Create database
CREATE DATABASE IF NOT EXISTS `arkafood` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `arkafood`;

-- Products table
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- News / blog table
CREATE TABLE IF NOT EXISTS news (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  excerpt TEXT,
  content TEXT,
  image VARCHAR(255),
  published_at DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NULL,
  customer_name VARCHAR(255),
  phone VARCHAR(50),
  address TEXT,
  quantity INT DEFAULT 1,
  total_price DECIMAL(10,2) DEFAULT 0,
  status ENUM('pending','confirmed','shipped','cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Contacts (optional: for contact form submissions)
CREATE TABLE IF NOT EXISTS contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admins (for simple admin login)
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Minimal seed data for products
INSERT INTO products (name, slug, description, price, image)
VALUES
('Jamur Krispy Tiram Original','jamur-tiram-original','Jamur tiram crispy dengan bumbu original yang renyah dan gurih.',7000,'assets/images/jkTiramOriginal.png'),
('Jamur Krispy Tiram Pedas','jamur-tiram-pedas','Jamur tiram crispy dengan bumbu pedas yang menggugah selera.',7000,'assets/images/jkTiramPedas.png'),
('Kulit Krezi Pedas','kulit-krezi-pedas','Kulit Krezi Pedas dengan kualitas premium dan rasa pedas yang menggugah selera.',7000,'assets/images/kkOriginal.png');

-- Minimal seed data for news
INSERT INTO news (title, slug, excerpt, content, image, published_at)
VALUES
('Peluncuran Produk Baru','peluncuran-produk-baru','Arka Food meluncurkan lini produk terbaru untuk memenuhi kebutuhan pasar.','Detail peluncuran produk baru...', 'assets/images/news/newsproduk.png','2025-11-09 09:00:00'),
('Kolaborasi Spesial','kolaborasi-spesial','Kolaborasi istimewa dengan chef ternama dalam pengembangan produk.','Detail kolaborasi...', 'assets/images/news/newskk.png','2025-11-05 10:00:00');
