CREATE DATABASE IF NOT EXISTS alunos_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

use alunos_database;

CREATE TABLE IF NOT EXISTS alunos(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    idade INT NOT NULL,
    texto_url VARCHAR(200),
    desenho_url VARCHAR(200)
);