-- Script de création de la base de données
CREATE DATABASE IF NOT EXISTS veille_info;
USE veille_info;

-- Table des utilisateurs
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_nom VARCHAR(50) NOT NULL,
    user_prenom VARCHAR(50) NOT NULL,
    user_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des thématiques
CREATE TABLE themes (
    theme_id INT PRIMARY KEY AUTO_INCREMENT,
    theme_nom VARCHAR(100) NOT NULL UNIQUE,
    theme_desc TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des ressources
CREATE TABLE resources (
    resource_id INT PRIMARY KEY AUTO_INCREMENT,
    resource_titre VARCHAR(150) NOT NULL,
    resource_desc TEXT NULL,
    resource_url VARCHAR(255) NOT NULL,
    theme_id INT,
    FOREIGN KEY (theme_id) REFERENCES themes(theme_id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des logs
CREATE TABLE logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insertion des données initiales
INSERT INTO users (user_nom, user_prenom, user_email, user_password) VALUES 
('Doe', 'John', 'john.doe@example.com', 'hashed_password');

INSERT INTO themes (theme_nom, theme_desc) VALUES 
('Programmation', 'Tout sur le développement'),
('Réseaux', 'Les fondamentaux des réseaux informatiques');

INSERT INTO resources (resource_titre, resource_desc, resource_url, theme_id) VALUES 
('Introduction à PHP', 'Guide pour débutants', 'http://example.com/php', 1),
('Bases du routage', 'Explication des protocoles', 'http://example.com/routage', 2);
