-- Criação do banco
CREATE SCHEMA IF NOT EXISTS clinica DEFAULT CHARACTER SET utf8mb4;
USE clinica;

-- Tabela: perfil
CREATE TABLE IF NOT EXISTS perfil (
  id_perfil INT AUTO_INCREMENT PRIMARY KEY,
  nome_perfil VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: usuario
CREATE TABLE IF NOT EXISTS usuario (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  id_perfil INT DEFAULT NULL,
  senha_temporaria TINYINT(1) DEFAULT 0,
  FOREIGN KEY (id_perfil) REFERENCES perfil(id_perfil)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: cliente
CREATE TABLE IF NOT EXISTS cliente (
  id_cliente INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  telefone VARCHAR(15) NOT NULL,
  endereco VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  data_nascimento DATE NOT NULL,
  senha varchar(16) NOT NULL,
  genero VARCHAR(1) NOT NULL,
  id_perfil INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: fornecedor
CREATE TABLE IF NOT EXISTS fornecedor (
  id_fornecedor INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  endereco VARCHAR(100) NOT NULL,
  telefone VARCHAR(15) NOT NULL,
  produto VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: funcionario
CREATE TABLE IF NOT EXISTS funcionario (
  id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  data_nascimento DATE NOT NULL,
  telefone VARCHAR(15) NOT NULL,
  endereco VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  genero VARCHAR(1) NOT NULL,
  cargo VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: procedimento
CREATE TABLE IF NOT EXISTS procedimento (
  id_procedimento INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL,
  descricao VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: produto
  CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(500) NOT NULL
);

-- Tabela: agendamento
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    procedimento VARCHAR(100) NOT NULL,
    data DATE NOT NULL,
    hora TIME NOT NULL
);

-- Inserindo perfis
INSERT INTO perfil (nome_perfil)
VALUES ('administrador'), ('recepcionista'), ('cliente');

-- Inserindo um usuário
INSERT INTO usuario (nome, senha, email, id_perfil)
VALUES ('julia silva', 'julia123', 'julia@julia', 1);
