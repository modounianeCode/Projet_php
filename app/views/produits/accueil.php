<!-- ══ HERO ══════════════════════════════════════════════ -->
<section class="hero">
  <div class="hero__content">
    <span class="hero__badge">🌿 Produits frais, directement du producteur</span>
    <h1 class="hero__title">La Marketplace Agricole<br><em>Intelligente</em> du Sénégal</h1>
    <p class="hero__sub">Achetez directement aux agriculteurs locaux. Fruits, légumes, céréales — livrés partout au Sénégal.</p>
    <div class="hero__btns">
      <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--green btn--lg">Explorer le catalogue →</a>
      <a href="<?= BASE_URL ?>auth/inscription" class="btn btn--outline btn--lg">Vendre mes produits</a>
    </div>
    <div class="hero__stats">
      <div><strong>500+</strong><span>Producteurs</span></div>
      <div><strong>2 000+</strong><span>Produits</span></div>
      <div><strong>10 zones</strong><span>de livraison</span></div>
    </div>
  </div>
  <div class="hero__deco" aria-hidden="true">
    <span style="top:10%;left:30%">🥭</span>
    <span style="top:55%;left:8%">🌽</span>
    <span style="top:15%;right:8%">🧅</span>
    <span style="top:70%;right:15%">🥜</span>
    <span style="top:40%;right:30%">🍅</span>
  </div>
</section>

<!-- ══ CATÉGORIES ═══════════════════════════════════════ -->
<section class="section">
  <div class="wrap">
    <div class="section__head">
      <h2>Explorer par catégorie</h2>
      <p>Des produits de saison, directement des champs</p>
    </div>
    <div class="cats">
      <?php
      $icones = ['Céréales'=>['🌾','#f5e6c8'],'Légumes'=>['🥬','#c8f5d4'],'Fruits'=>['🥭','#f5d4c8'],
                 'Légumineuses'=>['🫘','#d4c8f5'],'Tubercules'=>['🥔','#f5f0c8'],'Transformés'=>['🫙','#c8e6f5']];
      foreach ($icones as $nom => [$ico, $bg]):
      ?>
      <a href="<?= BASE_URL ?>produit/catalogue?cat=<?= urlencode($nom) ?>"
         class="cat-card" style="--bg:<?= $bg ?>">
        <span class="cat-card__ico"><?= $ico ?></span>
        <span class="cat-card__nom"><?= $nom ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ PRODUITS VEDETTES ════════════════════════════════ -->
<section class="section section--alt">
  <div class="wrap">
    <div class="section__head">
      <h2>🌟 Produits en vedette</h2>
      <a href="<?= BASE_URL ?>produit/catalogue" class="link-more">Voir tout →</a>
    </div>
    <div class="grid-produits">
      <?php foreach ($vedettes as $p): ?>
        <?php include __DIR__ . '/_card.php'; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ══ AVANTAGES ════════════════════════════════════════ -->
<section class="section">
  <div class="wrap">
    <div class="avantages">
      <div class="avantage">
        <div class="avantage__ico">🚚</div>
        <h3>Livraison rapide</h3>
        <p>1 à 4 jours selon votre zone au Sénégal</p>
      </div>
      <div class="avantage">
        <div class="avantage__ico">📱</div>
        <h3>Paiement mobile</h3>
        <p>Wave, Orange Money, Free Money acceptés</p>
      </div>
      <div class="avantage">
        <div class="avantage__ico">🌱</div>
        <h3>100 % local</h3>
        <p>Directement des exploitations agricoles</p>
      </div>
      <div class="avantage">
        <div class="avantage__ico">🔒</div>
        <h3>Paiement sécurisé</h3>
        <p>Vos données protégées à chaque transaction</p>
      </div>
    </div>
  </div>
</section>

<!-- ══ CTA VENDEUR ══════════════════════════════════════ -->
<?php if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'vendeur'): ?>
<section class="cta">
  <div class="wrap">
    <div class="cta__box">
      <div>
        <h2>Vous êtes agriculteur ?</h2>
        <p>Rejoignez AgroMarket et vendez vos produits à des milliers d'acheteurs.</p>
      </div>
      <a href="<?= BASE_URL ?>auth/inscription" class="btn btn--white btn--lg">Commencer gratuitement →</a>
    </div>
  </div>
</section>
<?php endif; ?>