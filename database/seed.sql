-- Insertion des utilisateurs de démonstration
USE `marketplace_agricole`;

-- Supprimer les utilisateurs existants pour les remplacer
DELETE FROM `users` WHERE `email` IN ('vendeur@demo.com', 'acheteur@demo.com');

-- Insérer les utilisateurs avec mots de passe hachés
INSERT INTO `users` (`nom`, `email`, `mot_de_passe`, `role`, `telephone`, `adresse`) VALUES
('Vendeur Demo', 'vendeur@demo.com', '$2y$10$R1Uf9NfmWF7DsoEHecwiQu2Tfzmy3WppscvLeNyGOz56TlT6hkTd6', 'vendeur', '77 123 45 67', 'Dakar, Sénégal'),
('Acheteur Demo', 'acheteur@demo.com', '$2y$10$R1Uf9NfmWF7DsoEHecwiQu2Tfzmy3WppscvLeNyGOz56TlT6hkTd6', 'acheteur', '77 234 56 78', 'Pikine, Sénégal');

-- Vérifier l'insertion
SELECT `id`, `nom`, `email`, `role` FROM `users` WHERE `email` IN ('vendeur@demo.com', 'acheteur@demo.com');
