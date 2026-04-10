CREATE DATABASE IF NOT EXISTS todo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE todo_app;

CREATE TABLE taches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    statut ENUM('a_faire', 'en_cours', 'termine') DEFAULT 'a_faire',
    priorite ENUM('basse', 'moyenne', 'haute') DEFAULT 'moyenne',
    date_limite DATETIME,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);