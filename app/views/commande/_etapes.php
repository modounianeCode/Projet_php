<?php
/* Composant barre d'étapes — variable $etapeActive attendue (1, 2, 3) */
$etapeActive = $etapeActive ?? 1;
$etapes = ['Livraison', 'Paiement', 'Confirmation'];
?>
<div class="etapes">
  <?php foreach ($etapes as $i => $label): ?>
    <div class="etape <?= ($i + 1) < $etapeActive ? 'etape--done' : (($i + 1) === $etapeActive ? 'etape--active' : '') ?>">
      <span class="etape__num"><?= ($i + 1) < $etapeActive ? '✓' : ($i + 1) ?></span>
      <?= $label ?>
    </div>
    <?php if ($i < count($etapes) - 1): ?>
      <div class="etape__sep <?= ($i + 1) < $etapeActive ? 'etape__sep--done' : '' ?>"></div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>