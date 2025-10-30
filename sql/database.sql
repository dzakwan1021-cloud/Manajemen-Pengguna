CREATE DATABASE IF NOT EXISTS user_management;
USE user_management;

-- Tabel users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  activation_token VARCHAR(100) DEFAULT NULL,
  status ENUM('inactive','active') DEFAULT 'inactive',
  reset_token VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel products
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) DEFAULT 0,
  stock INT DEFAULT 0,
  created_by INT,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);
