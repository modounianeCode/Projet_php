<section class="catalogue">
  <h2>🌾 Nos Produits Agricoles</h2>
  <div class="produits-grid">
    <?php foreach ($produits as $produit): ?>
      <div class="produit-card">
        <img src="<?= UPLOAD_URL . htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>">
        <h3><?= htmlspecialchars($produit['nom']) ?></h3>
        <p><?= number_format($produit['prix'], 0, ',', ' ') ?> FCFA / <?= htmlspecialchars($produit['unite']) ?></p>
        <a href="<?= BASE_URL ?>produit/detail/<?= $produit['id'] ?>" class="btn">Voir</a>
        <form action="<?= BASE_URL ?>panier/ajouter/<?= $produit['id'] ?>" method="POST" style="display:inline">
          <button type="submit" class="btn btn--green">🛒 Ajouter</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</section>