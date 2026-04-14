<section class="page-content">
  <div class="page-header">
    <h1>📦 Mes livraisons</h1>
    <p>Suivi du statut de vos commandes</p>
  </div>

  <?php if (empty($livraisons)): ?>
    <div class="empty-state">
      <p>Aucune livraison en cours.</p>
      <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--green">Faire un achat</a>
    </div>
  <?php else: ?>
    <div class="livraisons-list">
      <?php foreach ($livraisons as $l): ?>
        <div class="livraison-card">
          <div class="livraison-card__header">
            <h3>Commande #<?= $l['commande_id'] ?></h3>
            <span class="statut-badge statut--<?= $l['statut'] ?>">
              <?php
                $statuts = [
                  'en_attente'  => '⏳ En attente',
                  'en_cours'    => '🚚 En cours',
                  'livree'      => '✅ Livrée',
                  'echec'       => '❌ Échec',
                ];
                echo $statuts[$l['statut']] ?? ucfirst($l['statut']);
              ?>
            </span>
          </div>

          <div class="livraison-card__details">
            <div>
              <label>Zone</label>
              <span><?= htmlspecialchars($l['zone']) ?></span>
            </div>
            <div>
              <label>Frais</label>
              <span><?= number_format($l['frais'], 0, ',', ' ') ?> FCFA</span>
            </div>
            <div>
              <label>Date prévue</label>
              <span><?= date('d/m/Y', strtotime($l['date_prevue'])) ?></span>
            </div>
          </div>

          <div class="livraison-card__actions">
            <a href="<?= BASE_URL ?>livraison/detail/<?= $l['commande_id'] ?>" class="btn btn--outline btn--sm">Voir détails</a>
            <a href="<?= BASE_URL ?>commande/confirmation/<?= $l['commande_id'] ?>" class="btn btn--outline btn--sm">Commande</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
