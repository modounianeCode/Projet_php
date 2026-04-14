<div class="wrap" style="padding-top:2rem">

  <?php include __DIR__ . '/_etapes.php'; /* barre de progression */ ?>

  <div class="checkout-layout">
    <div class="checkout-main">
      <h2>🚚 Informations de Livraison</h2>

      <form method="POST" action="<?= BASE_URL ?>commande/recapitulatif" class="form-card">

        <div class="form-group">
          <label>Adresse de livraison complète *</label>
          <textarea name="adresse" rows="3" required
                    placeholder="Ex : 45 Rue Carnot, Plateau, Dakar"></textarea>
        </div>

        <div class="form-group">
          <label>Zone de livraison *</label>
          <div class="zones-grid">
            <?php foreach ($zones as $nom => $info): ?>
            <label class="zone-opt">
              <input type="radio" name="zone" value="<?= $nom ?>"
                     <?= $nom === 'Dakar' ? 'checked' : '' ?>>
              <div class="zone-card js-zone-card">
                <strong><?= $nom ?></strong>
                <span class="zone-prix"><?= number_format($info['frais'], 0, ',', ' ') ?> FCFA</span>
                <span class="zone-delai">≈ <?= $info['delai'] ?> j.</span>
              </div>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <button type="submit" class="btn btn--green btn--lg">Continuer → Récapitulatif</button>
      </form>
    </div>

    <!-- Mini résumé panier -->
    <?php include __DIR__ . '/_mini-panier.php'; ?>
  </div>
</div>

<script>
document.querySelectorAll('input[name="zone"]').forEach(r => {
  const highlight = () => {
    document.querySelectorAll('.js-zone-card').forEach(c => c.classList.remove('zone-card--active'));
    r.closest('.zone-opt').querySelector('.js-zone-card').classList.add('zone-card--active');
  };
  r.addEventListener('change', highlight);
  if (r.checked) highlight();
});
</script>