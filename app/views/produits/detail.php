<div class="wrap" style="padding-top:1.5rem">

  <!-- Fil d'Ariane -->
  <nav class="breadcrumb">
    <a href="<?= BASE_URL ?>produit">Accueil</a> /
    <a href="<?= BASE_URL ?>produit/catalogue">Catalogue</a> /
    <a href="<?= BASE_URL ?>produit/catalogue?cat=<?= urlencode($produit['categorie']) ?>"><?= htmlspecialchars($produit['categorie']) ?></a> /
    <span><?= htmlspecialchars($produit['nom']) ?></span>
  </nav>

  <div class="detail-layout">

    <!-- Image + vendeur -->
    <div>
      <div class="detail-img">
        <?php if (!empty($produit['image']) && $produit['image'] !== 'default.png'): ?>
          <img src="<?= UPLOAD_URL . htmlspecialchars($produit['image']) ?>"
               alt="<?= htmlspecialchars($produit['nom']) ?>">
        <?php else: ?>
          <div class="detail-img__placeholder">🌿</div>
        <?php endif; ?>
      </div>

      <div class="vendeur-card">
        <h4>👤 Vendeur</h4>
        <div class="vendeur-card__nom"><?= htmlspecialchars($produit['vendeur_nom']) ?></div>
        <?php if (!empty($produit['vendeur_adresse'])): ?>
          <div class="vendeur-card__info">📍 <?= htmlspecialchars($produit['vendeur_adresse']) ?></div>
        <?php endif; ?>
        <?php if (!empty($produit['vendeur_tel'])): ?>
          <div class="vendeur-card__info">📞 <?= htmlspecialchars($produit['vendeur_tel']) ?></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Infos produit -->
    <div class="detail-info">
      <div class="detail-cat"><?= htmlspecialchars($produit['categorie']) ?></div>
      <h1 class="detail-nom"><?= htmlspecialchars($produit['nom']) ?></h1>

      <div class="detail-prix">
        <strong><?= number_format($produit['prix'], 0, ',', ' ') ?></strong>
        <span>FCFA / <?= htmlspecialchars($produit['unite']) ?></span>
      </div>

      <div class="detail-stock <?= $produit['stock'] > 0 ? 'in-stock' : 'out-stock' ?>">
        <?= $produit['stock'] > 0
            ? "✅ En stock ({$produit['stock']} {$produit['unite']} disponibles)"
            : '❌ Rupture de stock' ?>
      </div>

      <div class="detail-desc">
        <h3>Description</h3>
        <p><?= nl2br(htmlspecialchars($produit['description'])) ?></p>
      </div>

      <?php if ($produit['stock'] > 0): ?>
      <form action="<?= BASE_URL ?>panier/ajouter/<?= $produit['id'] ?>" method="POST" class="detail-form">
        <div class="qty-row">
          <label>Quantité (<?= htmlspecialchars($produit['unite']) ?>)</label>
          <div class="qty-ctrl">
            <button type="button" onclick="changeQty(-1)">−</button>
            <input type="number" name="quantite" id="qty-input"
                   value="1" min="1" max="<?= $produit['stock'] ?>">
            <button type="button" onclick="changeQty(1)">+</button>
          </div>
        </div>
        <div class="prix-total">
          Total : <strong id="prix-total"><?= number_format($produit['prix'], 0, ',', ' ') ?> FCFA</strong>
        </div>
        <button type="submit" class="btn btn--green btn--lg btn--block">🛒 Ajouter au panier</button>
      </form>
      <?php else: ?>
        <p class="msg-rupture">Ce produit est actuellement indisponible.</p>
      <?php endif; ?>

      <div class="detail-garanties">
        <span>🚚 Livraison partout au Sénégal</span>
        <span>📱 Paiement Mobile Money</span>
        <span>🌱 Produit local frais</span>
      </div>
    </div>
  </div>

  <!-- Produits similaires -->
  <?php
  $filtres = array_filter($similaires, fn($s) => $s['id'] !== $produit['id']);
  if (!empty($filtres)):
  ?>
  <section style="margin-top:3rem;padding-top:2rem;border-top:1px solid #eee">
    <h2 style="font-family:'Playfair Display',serif;color:#1B5E20;margin-bottom:1.5rem">Vous aimerez aussi</h2>
    <div class="grid-produits">
      <?php foreach (array_slice($filtres, 0, 4) as $p): ?>
        <?php include __DIR__ . '/_card.php'; ?>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

</div>

<script>
const prixUnit = <?= (float)$produit['prix'] ?>;
function changeQty(delta) {
  const input = document.getElementById('qty-input');
  let val = parseInt(input.value) + delta;
  val = Math.max(1, Math.min(parseInt(input.max), val));
  input.value = val;
  document.getElementById('prix-total').textContent =
    (prixUnit * val).toLocaleString('fr-FR') + ' FCFA';
}
document.getElementById('qty-input')?.addEventListener('input', function () {
  document.getElementById('prix-total').textContent =
    (prixUnit * parseInt(this.value || 1)).toLocaleString('fr-FR') + ' FCFA';
});
</script>