<aside class="resume-box">
  <h3>Votre panier</h3>
  <?php
  $sousT = 0;
  foreach ($lignes as $l):
    $st = $l['produit']['prix'] * $l['quantite'];
    $sousT += $st;
  ?>
  <div class="resume-ligne">
    <span><?= htmlspecialchars($l['produit']['nom']) ?> × <?= $l['quantite'] ?></span>
    <span><?= number_format($st, 0, ',', ' ') ?> FCFA</span>
  </div>
  <?php endforeach; ?>
  <div class="resume-ligne resume-total">
    <span>Sous-total</span>
    <strong><?= number_format($sousT, 0, ',', ' ') ?> FCFA</strong>
  </div>
</aside>