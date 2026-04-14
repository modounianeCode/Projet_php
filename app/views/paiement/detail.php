<section class="page-content">
  <div class="page-header">
    <h1>💳 Détail du paiement</h1>
    <a href="<?= BASE_URL ?>paiement" class="btn btn--outline btn--sm">← Retour</a>
  </div>

  <div class="details-card">
    <div class="details-row">
      <h3>Paiement</h3>
    </div>

    <div class="details-grid">
      <div>
        <label>Référence</label>
        <code><?= htmlspecialchars($paiement['reference']) ?></code>
      </div>
      <div>
        <label>Montant</label>
        <span><?= number_format($paiement['montant'], 0, ',', ' ') ?> FCFA</span>
      </div>
      <div>
        <label>Méthode</label>
        <span><?= ucfirst(str_replace('_', ' ', $paiement['methode'])) ?></span>
      </div>
      <div>
        <label>Statut</label>
        <span class="statut-badge statut--<?= $paiement['statut'] ?>">
          <?= $paiement['statut'] === 'confirme' ? '✅ Confirmé' : '⏳ En attente' ?>
        </span>
      </div>
      <div>
        <label>Commande</label>
        <a href="<?= BASE_URL ?>commande/confirmation/<?= $commande['id'] ?>">#<?= $commande['id'] ?></a>
      </div>
      <div>
        <label>Zone de livraison</label>
        <span><?= htmlspecialchars($commande['zone_livraison']) ?></span>
      </div>
    </div>

    <div class="details-row">
      <h3>Montants</h3>
    </div>

    <div class="details-grid">
      <div>
        <label>Total produits</label>
        <span><?= number_format($commande['total'] - $commande['frais_livraison'], 0, ',', ' ') ?> FCFA</span>
      </div>
      <div>
        <label>Frais de livraison</label>
        <span><?= number_format($commande['frais_livraison'], 0, ',', ' ') ?> FCFA</span>
      </div>
      <div>
        <label><strong>Total</strong></label>
        <span><strong><?= number_format($commande['total'], 0, ',', ' ') ?> FCFA</strong></span>
      </div>
    </div>
  </div>
</section>
