-- sql/schema.sql - estrutura inicial (MySQL)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  pass_hash VARCHAR(255) NOT NULL,
  role ENUM('empresa','hacker','admin') NOT NULL DEFAULT 'empresa',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS hacker_profiles (
  user_id INT PRIMARY KEY,
  bio TEXT,
  skills VARCHAR(255),
  contact VARCHAR(120),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS sites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  url VARCHAR(255) NOT NULL,
  notes VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  site_id INT NOT NULL,
  kind ENUM('previo','avancado') NOT NULL DEFAULT 'previo',
  status ENUM('rascunho','publicado') NOT NULL DEFAULT 'rascunho',
  summary TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (site_id) REFERENCES sites(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS findings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  report_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  severity ENUM('baixa','media','alta','critica') NOT NULL,
  description TEXT,
  recommendation TEXT,
  FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);

-- perfil_usuarios: dados extras para cada usuário
CREATE TABLE IF NOT EXISTS perfil_usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  tipo ENUM('HACKER','CLIENTE') NOT NULL,
  cpf VARCHAR(20) DEFAULT NULL,       -- para pessoa física
  cnpj VARCHAR(20) DEFAULT NULL,      -- para pessoa jurídica
  cidade VARCHAR(120) DEFAULT NULL,
  telefone VARCHAR(30) DEFAULT NULL,
  empresa VARCHAR(200) DEFAULT NULL,  -- opcional para cliente
  habilidades TEXT DEFAULT NULL,      -- opcional para hacker
  site_portfolio VARCHAR(255) DEFAULT NULL,
  bio TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_tipo (user_id)
);
-- índice para buscar por user_id
CREATE INDEX idx_user_id ON perfil_usuarios(user_id);
