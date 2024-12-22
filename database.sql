-- Création de la base de données
CREATE DATABASE IF NOT EXISTS zirari_manager;

-- Sélection de la base de données
USE zirari_manager;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    archive BOOLEAN DEFAULT FALSE,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des livres
CREATE TABLE IF NOT EXISTS livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    annee_publication INT,
    genre VARCHAR(100),
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion d'un utilisateur admin (exemple)
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) 
VALUES ('Admin', 'admin@bibliotheque.com', 'motdepassehaché', 'admin');

-- Insertion d'un utilisateur (exemple)
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) 
VALUES ('Jean Dupont', 'jean.dupont@example.com', 'motdepassehaché', 'user');

-- Insertion d'un livre (exemple)
INSERT INTO livres (titre, auteur, annee_publication, genre) 
VALUES ('Le Petit Prince', 'Antoine de Saint-Exupéry', 1943, 'Fiction');

-- Insertion d'un autre livre (exemple)
INSERT INTO livres (titre, auteur, annee_publication, genre) 
VALUES ('1984', 'George Orwell', 1949, 'Dystopie');
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) 
VALUES ('Admin', 'nada@admin.com', '123456', 'admin');
ALTER TABLE utilisateurs ADD COLUMN valide BOOLEAN DEFAULT FALSE;
