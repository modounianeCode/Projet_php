<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= APP_NAME ?> — Marketplace Agricole</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<nav class="nav">
  <div class="nav__inner">

    <a href="<?= BASE_URL ?>produit" class="nav__brand">
      🌾 <strong>Agro</strong>Market
    </a>

    <form class="nav__search" action="<?= BASE_URL ?>produit/catalogue" method="GET">
      <input type="text" name="q" placeholder="Tomates, mil, mangues…"
             value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      <button type="submit">🔍</button>
    </form>

    <div class="nav__links">
      <a href="<?= BASE_URL ?>produit/catalogue">Catalogue</a>

      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= BASE_URL ?>panier" class="nav__cart">
          🛒
          <?php $nbPanier = array_sum($_SESSION['panier'] ?? []); ?>
          <?php if ($nbPanier > 0): ?>
            <span class="nav__badge"><?= $nbPanier ?></span>
          <?php endif; ?>
        </a>

        <div class="nav__user">
          <button class="nav__user-btn">
            👤 <?= htmlspecialchars($_SESSION['user_nom']) ?> ▾
          </button>
          <ul class="nav__dropdown">
            <li><a href="<?= BASE_URL ?>dashboard/index">Mon Dashboard</a></li>
            <?php if ($_SESSION['role'] === 'vendeur'): ?>
              <li><a href="<?= BASE_URL ?>produit/ajouter">+ Ajouter un produit</a></li>
            <?php endif; ?>
            <li class="sep"></li>
            <li><a href="<?= BASE_URL ?>auth/deconnexion" class="danger">Déconnexion</a></li>
          </ul>
        </div>

      <?php else: ?>
        <a href="<?= BASE_URL ?>auth/login" class="btn btn--outline btn--sm">Connexion</a>
        <a href="<?= BASE_URL ?>auth/inscription" class="btn btn--green btn--sm">S'inscrire</a>
      <?php endif; ?>
    </div>

    <button class="nav__burger" onclick="this.closest('.nav').querySelector('.nav__links').classList.toggle('open')">☰</button>
  </div>
</nav>

<?php if (!empty($_SESSION['flash'])): ?>
  <div class="flash flash--<?= $_SESSION['flash']['type'] ?>" id="flash">
    <?= htmlspecialchars($_SESSION['flash']['message']) ?>
    <button onclick="this.parentElement.remove()">✕</button>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<main>