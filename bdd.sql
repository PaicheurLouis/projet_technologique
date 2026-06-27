CREATE DATABASE IF NOT EXISTS qcm_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE qcm_app;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    bloque TINYINT(1) DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    reponse1 VARCHAR(255) NOT NULL,
    reponse2 VARCHAR(255) NOT NULL,
    reponse3 VARCHAR(255) NOT NULL,
    reponse4 VARCHAR(255) NOT NULL,
    bonne_reponse INT NOT NULL,
    categorie VARCHAR(100) DEFAULT 'general'
);

CREATE TABLE tentatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    score FLOAT NOT NULL,
    date_tentative DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

CREATE TABLE reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tentative_id INT NOT NULL,
    question_id INT NOT NULL,
    reponse_utilisateur INT NOT NULL,
    correcte TINYINT(1) NOT NULL,
    FOREIGN KEY (tentative_id) REFERENCES tentatives(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

CREATE DATABASE IF NOT EXISTS qcm_app
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE qcm_app;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    bloque TINYINT(1) DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tentatives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    score FLOAT NOT NULL,
    date_tentative DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reponses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tentative_id INT NOT NULL,
    question_id INT NOT NULL,
    reponse_utilisateur INT NOT NULL,
    correcte BOOLEAN NOT NULL
);