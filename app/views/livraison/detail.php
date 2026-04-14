<section class="page-content">
  <div class="page-header">
    <h1>📦 Détail de la livraison</h1>
    <a href="<?= BASE_URL ?>livraison" class="btn btn--outline btn--sm">← Retour</a>
  </div>

  <div class="details-card">
    <div class="details-row">
      <h3>Statut de livraison</h3>
    </div>

    <div class="livraison-status">
      <span class="statut-badge statut--<?= $livraison['statut'] ?>">
        <?php
          $statuts = [
            'en_attente'  => '⏳ En attente',
            'en_cours'    => '🚚 En cours',
            'livree'      => '✅ Livrée',
            'echec'       => '❌ Échec',
          ];
          echo $statuts[$livraison['statut']] ?? ucfirst($livraison['statut']);
        ?>
      </span>
    </div>

    <div class="details-grid">
      <div>
        <label>Commande</label>
        <a href="<?= BASE_URL ?>commande/confirmation/<?= $commande['id'] ?>">#<?= $commande['id'] ?></a>
      </div>
      <div>
        <label>Zone</label>
        <span><?= htmlspecialchars($livraison['zone']) ?></span>
      </div>
      <div>
        <label>Adresse</label>
        <span><?= htmlspecialchars($commande['adresse_livraison']) ?></span>
      </div>
      <div>
        <label>Frais de livraison</label>
        <span><?= number_format($livraison['frais'], 0, ',', ' ') ?> FCFA</span>
      </div>
      <div>
        <label>Date prévue</label>
        <span><?= date('d/m/Y', strtotime($livraison['date_prevue'])) ?></span>
      </div>
    </div>

    <div class="details-row">
      <h3>Articles livrés</h3>
    </div>

    <div class="items-list">
      <table class="table">
        <thead>
          <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Unité</th>
            <th>Prix unitaire</th>
            <th>Sous-total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lignes as $ligne): ?>
            <tr>
              <td><?= htmlspecialchars($ligne['produit_nom']) ?></td>
              <td><?= $ligne['quantite'] ?></td>
              <td><?= htmlspecialchars($ligne['unite']) ?></td>
              <td><?= number_format($ligne['prix_unitaire'], 0, ',', ' ') ?> FCFA</td>
              <td><?= number_format($ligne['quantite'] * $ligne['prix_unitaire'], 0, ',', ' ') ?> FCFA</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
