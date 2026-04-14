-- ============================================================================
-- AgroMarket - Schéma de Base de Données
-- Marketplace Agricole Sénégal
-- ============================================================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS `marketplace_agricole` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `marketplace_agricole`;

-- ============================================================================
-- TABLE: users (Utilisateurs)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `mot_de_passe` VARCHAR(255) NOT NULL,
  `role` ENUM('acheteur', 'vendeur') DEFAULT 'acheteur',
  `telephone` VARCHAR(20),
  `adresse` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_email` (`email`),
  INDEX `idx_role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: produits (Produits)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `produits` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `vendeur_id` INT NOT NULL,
  `nom` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `prix` DECIMAL(10, 2) NOT NULL,
  `unite` VARCHAR(20) DEFAULT 'kg',
  `stock` INT DEFAULT 0,
  `categorie` VARCHAR(50),
  `image` VARCHAR(255) DEFAULT 'default.svg',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`vendeur_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_vendeur` (`vendeur_id`),
  INDEX `idx_categorie` (`categorie`),
  INDEX `idx_nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: commandes (Commandes)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `acheteur_id` INT NOT NULL,
  `statut` ENUM('en_attente', 'payee', 'en_livraison', 'livree', 'annulee') DEFAULT 'en_attente',
  `total` DECIMAL(10, 2) NOT NULL,
  `adresse_livraison` VARCHAR(255),
  `zone_livraison` VARCHAR(50),
  `frais_livraison` DECIMAL(10, 2),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`acheteur_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_acheteur` (`acheteur_id`),
  INDEX `idx_statut` (`statut`),
  INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: commande_lignes (Lignes de Commande)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `commande_lignes` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `commande_id` INT NOT NULL,
  `produit_id` INT NOT NULL,
  `quantite` INT NOT NULL,
  `prix_unitaire` DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (`commande_id`) REFERENCES `commandes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`produit_id`) REFERENCES `produits`(`id`) ON DELETE RESTRICT,
  INDEX `idx_commande` (`commande_id`),
  INDEX `idx_produit` (`produit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: paiements (Paiements)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `paiements` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `commande_id` INT NOT NULL,
  `methode` VARCHAR(50),
  `statut` ENUM('en_attente', 'confirme', 'echouee') DEFAULT 'en_attente',
  `montant` DECIMAL(10, 2),
  `reference` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`commande_id`) REFERENCES `commandes`(`id`) ON DELETE CASCADE,
  INDEX `idx_commande` (`commande_id`),
  INDEX `idx_statut` (`statut`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: livraisons (Livraisons)
-- ============================================================================
CREATE TABLE IF NOT EXISTS `livraisons` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `commande_id` INT NOT NULL,
  `zone` VARCHAR(50),
  `frais` DECIMAL(10, 2),
  `statut` ENUM('en_attente', 'en_cours', 'livree', 'echec') DEFAULT 'en_attente',
  `date_prevue` DATE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`commande_id`) REFERENCES `commandes`(`id`) ON DELETE CASCADE,
  INDEX `idx_commande` (`commande_id`),
  INDEX `idx_statut` (`statut`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- DONNÉES DE DÉMONSTRATION
-- ============================================================================

-- Utilisateurs démo
INSERT INTO `users` (`nom`, `email`, `mot_de_passe`, `role`, `telephone`, `adresse`) VALUES
('Vendeur Demo', 'vendeur@demo.com', '$2y$10$YourHashedPasswordHere', 'vendeur', '77 123 45 67', 'Dakar'),
('Acheteur Demo', 'acheteur@demo.com', '$2y$10$YourHashedPasswordHere', 'acheteur', '77 234 56 78', 'Pikine');

-- Note: Les mots de passe hachés ci-dessus sont des exemples.
-- Utilisez PHP pour générer les vrais hachés:
-- password_hash('123456', PASSWORD_BCRYPT)

-- ============================================================================
-- INSTRUCTIONS D'IMPORT
-- ============================================================================
-- 
-- Pour importer ce fichier dans MySQL:
--
-- Option 1: Via ligne de commande
-- mysql -u root -p marketplace_agricole < database.sql
--
-- Option 2: Via phpMyAdmin
-- 1. Aller à Importer
-- 2. Sélectionner ce fichier
-- 3. Cliquer Exécuter
--
-- Option 3: Via MySQL Workbench
-- File > Run SQL Script > Sélectionner ce fichier
--
-- ============================================================================
