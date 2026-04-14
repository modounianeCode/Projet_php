<?php $etapeActive = 3; ?>
<div class="wrap wrap--narrow" style="padding-top:2rem">

  <?php include __DIR__ . '/_etapes.php'; ?>

  <div class="confirm-hero">
    <div class="confirm-hero__icon">🎉</div>
    <h1>Commande confirmée !</h1>
    <p>Commande <strong>#<?= $commande['id'] ?></strong></p>
    <?php if ($paiement): ?>
      <code>Réf : <?= htmlspecialchars($paiement['reference']) ?></code>
    <?php endif; ?>
    <div class="statut-badge statut--<?= $commande['statut'] ?>">
      <?= match($commande['statut']) {
        'payee'      => '✅ Payée',
        'en_attente' => '⏳ En attente de paiement',
        default      => $commande['statut']
      } ?>
    </div>
  </div>

  <div class="confirm-blocs">

    <div class="confirm-bloc">
      <h3>📦 Articles</h3>
      <?php foreach ($lignes as $l): ?>
      <div class="recap-ligne">
        <span><?= htmlspecialchars($l['produit_nom']) ?> × <?= $l['quantite'] ?> <?= $l['unite'] ?></span>
        <span><?= number_format($l['prix_unitaire'] * $l['quantite'], 0, ',', ' ') ?> FCFA</span>
      </div>
      <?php endforeach; ?>
      <div class="recap-ligne recap-ligne--total">
        <strong>Total</strong>
        <strong><?= number_format($commande['total'], 0, ',', ' ') ?> FCFA</strong>
      </div>
    </div>

    <?php if ($livraison): ?>
    <div class="confirm-bloc">
      <h3>🚚 Livraison</h3>
      <div class="info-grid">
        <div><label>Zone</label><span><?= htmlspecialchars($livraison['zone']) ?></span></div>
        <div><label>Adresse</label><span><?= htmlspecialchars($commande['adresse_livraison']) ?></span></div>
        <div><label>Date prévue</label><span><?= date('d/m/Y', strtotime($livraison['date_prevue'])) ?></span></div>
        <div>
          <label>Statut</label>
          <span class="statut-badge statut--<?= $livraison['statut'] ?>">
            <?= match($livraison['statut']) {
              'en_attente' => '⏳ En attente',
              'en_cours'   => '🚚 En cours',
              'livree'     => '✅ Livrée',
              default      => $livraison['statut']
            } ?>
          </span>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($paiement): ?>
    <div class="confirm-bloc">
      <h3>💳 Paiement</h3>
      <div class="info-grid">
        <div><label>Méthode</label><span><?= ucfirst(str_replace('_', ' ', $paiement['methode'])) ?></span></div>
        <div><label>Montant</label><span><?= number_format($paiement['montant'], 0, ',', ' ') ?> FCFA</span></div>
        <div><label>Référence</label><code><?= htmlspecialchars($paiement['reference']) ?></code></div>
        <div>
          <label>Statut</label>
          <span class="statut-badge statut--<?= $paiement['statut'] ?>">
            <?= $paiement['statut'] === 'confirme' ? '✅ Confirmé' : '⏳ En attente' ?>
          </span>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>

  <div class="confirm-actions">
    <a href="<?= BASE_URL ?>dashboard/acheteur" class="btn btn--green">Mes commandes</a>
    <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--outline">Continuer mes achats</a>
  </div>

</div>