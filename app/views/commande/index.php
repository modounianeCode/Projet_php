<section class="page-content">
  <div class="page-header">
    <h1>🛒 Votre panier</h1>
    <p>Consultez vos commandes</p>
  </div>

  <?php if (empty($commandes)): ?>
    <div class="empty-state">
      <p>Aucune commande pour le moment.</p>
      <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--green">Commencer vos achats</a>
    </div>
  <?php else: ?>
    <div class="commandes-list">
      <?php foreach ($commandes as $c): ?>
        <div class="commande-card">
          <h3>Commande #<?= $c['id'] ?></h3>
          <p>
            <strong>Statut :</strong>
            <span class="statut-badge statut--<?= $c['statut'] ?>">
              <?php
                $statuts = [
                  'en_attente' => '⏳ En attente',
                  'payee'      => '✅ Payée',
                  'en_livraison' => '🚚 En livraison',
                  'livree'     => '📦 Livrée',
                  'annulee'    => '❌ Annulée',
                ];
                echo $statuts[$c['statut']] ?? ucfirst($c['statut']);
              ?>
            </span>
          </p>
          <p><strong>Total :</strong> <?= number_format($c['total'], 0, ',', ' ') ?> FCFA</p>
          <a href="<?= BASE_URL ?>commande/confirmation/<?= $c['id'] ?>" class="btn btn--outline">Voir détails</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
