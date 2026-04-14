<div class="wrap" style="padding-top:2rem">
  <h1>🛒 Mon Panier</h1>

  <?php if (empty($lignes)): ?>
    <div class="empty">
      <div style="font-size:5rem">🛒</div>
      <h3>Votre panier est vide</h3>
      <p>Découvrez nos produits agricoles frais !</p>
      <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--green">Voir le catalogue</a>
    </div>

  <?php else: ?>
    <div class="panier-layout">

      <!-- Articles -->
      <div class="panier-articles">
        <?php foreach ($lignes as $ligne):
          $p = $ligne['produit']; ?>
        <div class="panier-ligne">

          <div class="panier-ligne__img">
            <?php if (!empty($p['image']) && $p['image'] !== 'default.png'): ?>
              <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
            <?php else: ?>
              <div class="card__img-placeholder" style="font-size:2rem">🌿</div>
            <?php endif; ?>
          </div>

          <div class="panier-ligne__info">
            <a href="<?= BASE_URL ?>produit/detail/<?= $p['id'] ?>">
              <strong><?= htmlspecialchars($p['nom']) ?></strong>
            </a>
            <div style="font-size:.85rem;color:#757575"><?= htmlspecialchars($p['categorie']) ?></div>
            <div style="font-size:.9rem;color:#388E3C">
              <?= number_format($p['prix'], 0, ',', ' ') ?> FCFA / <?= htmlspecialchars($p['unite']) ?>
            </div>
          </div>

          <form action="<?= BASE_URL ?>panier/modifier/<?= $p['id'] ?>" method="POST" class="panier-ligne__qty">
            <div class="qty-ctrl">
              <button type="button" onclick="stepQty(this,-1)">−</button>
              <input type="number" name="quantite"
                     value="<?= $ligne['quantite'] ?>"
                     min="1" max="<?= $p['stock'] ?>"
                     onchange="this.form.submit()">
              <button type="button" onclick="stepQty(this,1)">+</button>
            </div>
          </form>

          <div class="panier-ligne__total">
            <?= number_format($ligne['sousTotal'], 0, ',', ' ') ?> FCFA
          </div>

          <a href="<?= BASE_URL ?>panier/retirer/<?= $p['id'] ?>" class="panier-ligne__del"
             onclick="return confirm('Retirer cet article ?')">✕</a>

        </div>
        <?php endforeach; ?>

        <div class="panier-actions">
          <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--outline">← Continuer les achats</a>
          <a href="<?= BASE_URL ?>panier/vider" class="btn btn--danger"
             onclick="return confirm('Vider le panier ?')">🗑 Vider</a>
        </div>
      </div>

      <!-- Résumé -->
      <aside class="resume-box">
        <h3>Résumé</h3>
        <div class="resume-ligne">
          <span>Sous-total</span>
          <strong><?= number_format($sousTotal, 0, ',', ' ') ?> FCFA</strong>
        </div>
        <div class="resume-ligne">
          <span>Livraison</span>
          <span style="color:#757575">Calculée à l'étape suivante</span>
        </div>
        <div class="resume-ligne resume-total">
          <span>Estimé</span>
          <strong><?= number_format($sousTotal, 0, ',', ' ') ?> FCFA</strong>
        </div>
        <div class="pay-icons">
          <span>🔵 Wave</span><span>🟠 Orange</span>
          <span>🟣 Free</span><span>💵 Cash</span>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?= BASE_URL ?>commande" class="btn btn--green btn--lg btn--block">Commander →</a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>auth/login" class="btn btn--green btn--lg btn--block">
            Se connecter pour commander
          </a>
        <?php endif; ?>
      </aside>

    </div>
  <?php endif; ?>
</div>

<script>
function stepQty(btn, delta) {
  const input = btn.parentElement.querySelector('input[type="number"]');
  let val = parseInt(input.value) + delta;
  val = Math.max(1, Math.min(parseInt(input.max), val));
  input.value = val;
  input.form.submit();
}
</script>