# 🌾 AgroMarket - Marketplace Agricole Intelligente

Une plateforme e-commerce moderne et intuitive connectant les agriculteurs aux consommateurs au Sénégal. Achetez directement des producteurs locaux avec livraison dans 10 zones du pays.

## 🎯 À propos

**AgroMarket** est une marketplace agricole qui vise à :
- Connecter les agriculteurs locaux directement aux consommateurs
- Offrir une expérience d'achat transparente et sécurisée
- Fournir une livraison rapide dans tout le Sénégal
- Soutenir l'économie agricole locale

## 🚀 Fonctionnalités principales

### Pour les Acheteurs
- 🛒 **Catalogue interactif** - Filtrer par catégorie, prix, recherche textuelle
- 📦 **Panier persistant** - Gestion du panier avec session
- 🚚 **Livraison flexible** - 10 zones avec tarifs variables (1 500 - 7 000 FCFA)
- 💳 **Paiement multi-canal** - Wave, Orange Money, Free Money, Cash à la livraison
- 📊 **Dashboard acheteur** - Suivi des commandes et livraisons
- 🏠 **Accueil personnalisé** - Produits en vedette, catégories

### Pour les Vendeurs  
- 📝 **Gestion de produits** - Créer, modifier, supprimer
- 📊 **Dashboard vendeur** - Statistiques, chiffre d'affaires, alertes stock
- 📦 **Suivi des commandes** - Gestion des statuts de livraison
- 💰 **Tableau de bord financier** - CA, commandes, stock critique
- ⚠️ **Alertes stock** - Notification des articles sous 10 unités

### Système Général
- 👤 **Authentification sécurisée** - Inscription/Connexion avec hashage bcrypt
- 🔐 **Gestion des rôles** - Acheteur / Vendeur
- 📱 **Design responsive** - Mobile-first
- 🎨 **Interface moderne** - CSS personnalisé, UX optimisée
- 🌍 **Zones de livraison** - Dakar, Thiès, Saint-Louis, Ziguinchor, etc.

## 📋 Technologies utilisées

### Backend
- **PHP 8+** - Langage serveur
- **MySQL** - Base de données
- **PDO** - Accès sécurisé à la BD
- **Framework MVC custom** - Architecture légère et flexible

### Frontend
- **HTML5** - Structure
- **CSS3** - Styling responsive
- **JavaScript** - Interactivité
- **Google Fonts** - Fonts (Playfair Display, DM Sans)

### Outils
- **XAMPP** - Serveur local
- **Git** - Versioning

## 📁 Structure du projet

```
marketplace-agricole/
├── app/
│   ├── controllers/          # Logique métier
│   │   ├── AuthController.php
│   │   ├── ProduitController.php
│   │   ├── PanierController.php
│   │   ├── CommandeController.php
│   │   ├── DashboardController.php
│   │   ├── PaiementController.php
│   │   └── LivraisonController.php
│   ├── models/               # Accès données
│   │   ├── User.php
│   │   ├── Produit.php
│   │   ├── Commande.php
│   │   ├── Livraison.php
│   │   └── Paiement.php
│   └── views/                # Templates HTML
│       ├── auth/             # Login/Inscription
│       ├── produits/         # Catalogue, détails
│       ├── panier/           # Panier
│       ├── commande/         # Tunnel de commande (livraison → paiement → confirmation)
│       ├── dashboard/        # Vendeur et Acheteur
│       ├── paiement/         # Historique des paiements
│       ├── livraison/        # Suivi des livraisons
│       ├── layouts/          # Header, Footer, Layout
│       └── errors/           # Erreur 404
├── core/                     # Framework MVC
│   ├── App.php               # Dispatcher alternatif
│   ├── Router.php            # Routeur principal
│   ├── Controller.php        # Classe de base des contrôleurs
│   ├── Model.php             # Classe de base des modèles
│   └── Database.php          # Singleton PDO
├── config/
│   ├── config.php            # Configuration (DB, BASE_URL, chemins)
│   └── database.php          # Config DB (optionnel)
├── public/
│   ├── index.php             # Point d'entrée unique
│   ├── .htaccess             # Réécriture URL Apache
│   └── assets/
│       ├── css/              # Stylesheets
│       ├── js/               # Scripts
│       └── images/
│           └── produits/     # Images des produits
└── README.md
```

## 🛠️ Installation

### Prérequis
- PHP 8.0+
- MySQL 5.7+
- Apache avec mod_rewrite
- XAMPP ou serveur local équivalent

### Étapes d'installation

#### 1. Cloner le projet
```bash
git clone https://github.com/votre-repo/marketplace-agricole.git
cd marketplace-agricole
```

#### 2. Configurer la base de données

Créer la base de données :
```sql
CREATE DATABASE marketplace_agricole CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE marketplace_agricole;
```

Importer les tables :
```sql
-- Utilisateurs
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('acheteur','vendeur') DEFAULT 'acheteur',
  telephone VARCHAR(20),
  adresse VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Produits
CREATE TABLE produits (
  id INT PRIMARY KEY AUTO_INCREMENT,
  vendeur_id INT NOT NULL,
  nom VARCHAR(150) NOT NULL,
  description TEXT,
  prix DECIMAL(10,2) NOT NULL,
  unite VARCHAR(20),
  stock INT DEFAULT 0,
  categorie VARCHAR(50),
  image VARCHAR(255) DEFAULT 'default.svg',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (vendeur_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Commandes
CREATE TABLE commandes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  acheteur_id INT NOT NULL,
  statut ENUM('en_attente','payee','en_livraison','livree','annulee') DEFAULT 'en_attente',
  total DECIMAL(10,2) NOT NULL,
  adresse_livraison VARCHAR(255),
  zone_livraison VARCHAR(50),
  frais_livraison DECIMAL(10,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (acheteur_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Lignes de commande
CREATE TABLE commande_lignes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  commande_id INT NOT NULL,
  produit_id INT NOT NULL,
  quantite INT NOT NULL,
  prix_unitaire DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
  FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- Paiements
CREATE TABLE paiements (
  id INT PRIMARY KEY AUTO_INCREMENT,
  commande_id INT NOT NULL,
  methode VARCHAR(50),
  statut ENUM('en_attente','confirme') DEFAULT 'en_attente',
  montant DECIMAL(10,2),
  reference VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE
);

-- Livraisons
CREATE TABLE livraisons (
  id INT PRIMARY KEY AUTO_INCREMENT,
  commande_id INT NOT NULL,
  zone VARCHAR(50),
  frais DECIMAL(10,2),
  statut ENUM('en_attente','en_cours','livree','echec') DEFAULT 'en_attente',
  date_prevue DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE
);
```

#### 3. Configurer le projet

Éditer `config/config.php` :
```php
define('DB_HOST',    'localhost');
define('DB_NAME',    'marketplace_agricole');
define('DB_USER',    'root');
define('DB_PASS',    '');
```

#### 4. Configurer Apache

Créer `.htaccess` dans `/public` :
```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /marketplace-agricole/public/
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>
```

#### 5. Définir les permissions
```bash
chmod 755 public/assets/images/produits/
chmod 644 public/assets/css/style.css
chmod 644 public/assets/js/main.js
```

## 🔐 Comptes de démonstration

### Vendeur
- Email : `vendeur@demo.com`
- Mot de passe : `123456`

### Acheteur
- Email : `acheteur@demo.com`
- Mot de passe : `123456`

## 🌐 Routes principales

### Authentication
- `GET|POST  /auth/login`        - Connexion
- `GET|POST  /auth/inscription`  - Inscription
- `GET       /auth/deconnexion`  - Déconnexion

### Produits
- `GET       /produit`            - Accueil
- `GET       /produit/catalogue`  - Catalogue avec filtres
- `GET       /produit/detail/{id}` - Détail produit
- `GET       /produit/ajouter`    - Formulaire (vendeur)
- `POST      /produit/enregistrer` - Créer produit
- `GET       /produit/modifier/{id}` - Éditer (vendeur)
- `POST      /produit/mettreAJour/{id}` - Sauvegarder édition
- `GET       /produit/supprimer/{id}` - Supprimer produit

### Panier
- `GET       /panier`             - Voir le panier
- `POST      /panier/ajouter/{id}` - Ajouter produit
- `POST      /panier/modifier/{id}` - Changer quantité
- `GET       /panier/retirer/{id}` - Retirer article
- `GET       /panier/vider`       - Vider tout

### Commande
- `GET       /commande`           - Étape 1 : Livraison
- `POST      /commande/recapitulatif` - Étape 2 : Récapitulatif
- `POST      /commande/passer`    - Étape 3 : Créer commande
- `GET       /commande/confirmation/{id}` - Confirmation

### Dashboard
- `GET       /dashboard/index`    - Redirection auto acheteur/vendeur
- `GET       /dashboard/vendeur`  - Stats vendeur
- `GET       /dashboard/acheteur` - Commandes acheteur
- `POST      /dashboard/updateStatutCommande/{id}` - Changer statut

### Autres
- `GET       /paiement`           - Historique paiements
- `GET       /livraison`          - Suivi livraisons

## 🎨 Catégories de produits

- 🌾 Céréales
- 🥬 Légumes
- 🥭 Fruits
- 🫘 Légumineuses
- 🥔 Tubercules
- 🫙 Transformés

## 🚚 Zones de livraison

| Zone | Frais | Délai |
|------|-------|-------|
| Dakar | 1 500 FCFA | 1 jour |
| Pikine | 1 500 FCFA | 1 jour |
| Guediawaye | 2 000 FCFA | 1 jour |
| Thiès | 3 000 FCFA | 2 jours |
| Mbour | 3 500 FCFA | 2 jours |
| Kaolack | 4 000 FCFA | 2 jours |
| Diourbel | 4 000 FCFA | 2 jours |
| Touba | 4 500 FCFA | 3 jours |
| Saint-Louis | 5 000 FCFA | 3 jours |
| Ziguinchor | 7 000 FCFA | 4 jours |

## 💳 Méthodes de paiement

- 💳 **Wave** - Paiement mobile
- 🟠 **Orange Money** - Paiement mobile
- 🟣 **Free Money** - Paiement mobile
- 🚚 **Cash à la livraison** - Paiement à la réception

## 📊 Architecture MVC

```
REQUEST
   ↓
public/index.php (Front Controller)
   ↓
Router (core/Router.php)
   ↓
Controller (app/controllers/*)
   ↓
Model (app/models/*)
   ↓
Database (core/Database.php - MySQLi/PDO)
   ↓
View (app/views/*)
   ↓
Response (HTML)
```

## 🔐 Sécurité

- ✅ **Passwords** - Hashage bcrypt (`password_hash()`)
- ✅ **SQL Injection** - Requêtes préparées PDO
- ✅ **XSS** - Échappement HTML (`htmlspecialchars()`)
- ✅ **CSRF** - Sessions sécurisées
- ✅ **Authentification** - Contrôle d'accès par rôle
- ✅ **Autorisation** - Vérification de propriété (vendeur/commande)

## 🚀 Déploiement

### Sur un serveur

1. Télécharger les fichiers via FTP
2. Créer la base de données
3. Éditer `config/config.php` avec les identifiants serveur
4. Configurer Apache `.htaccess`
5. Gérer les permissions des dossiers

### Variables d'environnement (optionnel)

```bash
export DB_HOST=localhost
export DB_NAME=marketplace_agricole
export DB_USER=user
export DB_PASS=password
```

## 📝 Contribution

Les contributions sont bienvenues ! Pour contribuer :

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 🐛 Signaler un bug

Utilisez la section [Issues](https://github.com/votre-repo/issues) pour signaler des bugs.

## 📄 Licence

Ce projet est licencié sous la Licence MIT - voir le fichier [LICENSE](LICENSE) pour les détails.

## 👥 Auteur

**Équipe marketplace-agricole** (8 développeurs)
- 2024-2026

## 📞 Support

Pour toute question ou assistance :
- Email : support@agromarket.sn
- WhatsApp : +221 77 XXX XX XX

---

**Fait avec ❤️ au Sénégal** 🇸🇳
