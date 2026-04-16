<?php
/* Composant réutilisable — attendu : $p (tableau produit avec vendeur_nom) */
?>
<article class="card">
  <a href="<?= BASE_URL ?>produit/detail/<?= $p['id'] ?>" class="card__img-wrap">
  <?php if (!empty($p['image']) && $p['image'] !== 'default.svg' && $p['image'] !== 'default.png'): ?>   
  <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>"
           alt="<?= htmlspecialchars($p['nom']) ?>" class="card__img" loading="lazy">
    <?php else: ?>
      <div class="card__img-placeholder">🌿</div>
    <?php endif; ?>
    <?php if ($p['stock'] > 0 && $p['stock'] < 10): ?>
      <span class="card__badge">Stock limité</span>
    <?php elseif ($p['stock'] === 0): ?>
      <span class="card__badge card__badge--out">Épuisé</span>
    <?php endif; ?>
  </a>

  <div class="card__body">
    <div class="card__cat"><?= htmlspecialchars($p['categorie']) ?></div>
    <h3 class="card__nom">
      <a href="<?= BASE_URL ?>produit/detail/<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></a>
    </h3>
    <div class="card__vendeur">👤 <?= htmlspecialchars($p['vendeur_nom']) ?></div>

    <div class="card__footer">
      <div class="card__prix">
        <strong><?= number_format($p['prix'], 0, ',', ' ') ?></strong>
        <span>FCFA / <?= htmlspecialchars($p['unite']) ?></span>
      </div>
      <?php if ($p['stock'] > 0): ?>
      <form action="<?= BASE_URL ?>panier/ajouter/<?= $p['id'] ?>" method="POST" class="js-add-form">
        <input type="hidden" name="quantite" value="1">
        <button type="submit" class="card__btn-cart" title="Ajouter au panier">🛒</button>
      </form>
      <?php endif; ?>
    </div>
  </div>
</article>
