<?php $etapeActive = 2; ?>
<div class="wrap" style="padding-top:2rem">

  <?php include __DIR__ . '/_etapes.php'; ?>

  <div class="checkout-layout">
    <div class="checkout-main">
      <h2>💳 Récapitulatif &amp; Paiement</h2>

      <!-- Articles -->
      <div class="recap-bloc">
        <h3>Articles</h3>
        <?php foreach ($lignes as $l): ?>
        <div class="recap-ligne">
          <span><?= htmlspecialchars($l['produit']['nom']) ?>
                <em>× <?= $l['quantite'] ?> <?= htmlspecialchars($l['produit']['unite']) ?></em></span>
          <span><?= number_format($l['sousTotal'], 0, ',', ' ') ?> FCFA</span>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Livraison -->
      <div class="recap-bloc">
        <h3>Livraison</h3>
        <div class="recap-ligne"><span>Zone</span><strong><?= htmlspecialchars($zone) ?></strong></div>
        <div class="recap-ligne"><span>Frais</span><span><?= number_format($frais, 0, ',', ' ') ?> FCFA</span></div>
        <div class="recap-ligne"><span>Adresse</span><span><?= htmlspecialchars($adresse) ?></span></div>
      </div>

      <!-- Mode de paiement -->
      <form method="POST" action="<?= BASE_URL ?>commande/passer">
        <div class="recap-bloc">
          <h3>Mode de paiement</h3>
          <div class="pay-options">
            <?php foreach ($methodes as $val => $label): ?>
            <label class="pay-opt">
              <input type="radio" name="methode" value="<?= $val ?>"
                     <?= $val === 'wave' ? 'checked' : '' ?>>
              <div class="pay-card js-pay-card">
                <span class="pay-logo pay-logo--<?= $val ?>">
                  <?= match($val) { 'wave'=>'W','orange_money'=>'OM','free_money'=>'FM','cash'=>'💵', default=>'?' } ?>
                </span>
                <div>
                  <strong><?= $label ?></strong>
                  <small><?= $val === 'cash' ? 'Payer à la réception' : 'Paiement mobile' ?></small>
                </div>
              </div>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Total final -->
        <div class="total-final">
          <div class="total-final__ligne"><span>Sous-total</span><span><?= number_format($sous, 0, ',', ' ') ?> FCFA</span></div>
          <div class="total-final__ligne"><span>Livraison</span><span><?= number_format($frais, 0, ',', ' ') ?> FCFA</span></div>
          <div class="total-final__ligne total-final__ligne--total">
            <span>TOTAL</span><strong><?= number_format($total, 0, ',', ' ') ?> FCFA</strong>
          </div>
        </div>

        <button type="submit" id="btn-pay" class="btn btn--green btn--lg btn--block">
          ✅ Confirmer et payer <?= number_format($total, 0, ',', ' ') ?> FCFA
        </button>
        <p style="text-align:center;font-size:.85rem;color:#757575;margin-top:.5rem">
          🔒 Paiement simulé — Aucun débit réel
        </p>
      </form>
    </div>

    <!-- Mini résumé -->
    <aside class="resume-box">
      <h3>Résumé</h3>
      <div class="resume-ligne"><span><?= count($lignes) ?> article<?= count($lignes)>1?'s':'' ?></span></div>
      <div class="resume-ligne"><span>Sous-total</span><span><?= number_format($sous, 0, ',', ' ') ?> FCFA</span></div>
      <div class="resume-ligne"><span>Livraison</span><span><?= number_format($frais, 0, ',', ' ') ?> FCFA</span></div>
      <div class="resume-ligne resume-total"><span>Total</span><strong><?= number_format($total, 0, ',', ' ') ?> FCFA</strong></div>
      <a href="<?= BASE_URL ?>commande" class="btn btn--outline btn--block" style="margin-top:1rem">← Modifier</a>
    </aside>
  </div>
</div>

<script>
document.querySelectorAll('input[name="methode"]').forEach(r => {
  const hi = () => {
    document.querySelectorAll('.js-pay-card').forEach(c => c.classList.remove('pay-card--active'));
    r.closest('.pay-opt').querySelector('.js-pay-card').classList.add('pay-card--active');
  };
  r.addEventListener('change', hi);
  if (r.checked) hi();
});
document.querySelector('form').addEventListener('submit', () => {
  document.getElementById('btn-pay').disabled = true;
  document.getElementById('btn-pay').textContent = '⏳ Traitement…';
});
</script>