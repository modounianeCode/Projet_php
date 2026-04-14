<section class="page-content">
  <div class="page-header">
    <h1>💳 Mes paiements</h1>
    <p>Historique de tous vos paiements</p>
  </div>

  <?php if (empty($paiements)): ?>
    <div class="empty-state">
      <p>Aucun paiement pour le moment.</p>
      <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--green">Commencer vos achats</a>
    </div>
  <?php else: ?>
    <div class="paiements-list">
      <table class="table">
        <thead>
          <tr>
            <th>Commande</th>
            <th>Montant</th>
            <th>Méthode</th>
            <th>Référence</th>
            <th>Statut</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($paiements as $p): ?>
            <tr>
              <td>
                <a href="<?= BASE_URL ?>commande/confirmation/<?= $p['commande_id'] ?>">
                  #<?= $p['commande_id'] ?>
                </a>
              </td>
              <td><?= number_format($p['montant'], 0, ',', ' ') ?> FCFA</td>
              <td><?= ucfirst(str_replace('_', ' ', $p['methode'])) ?></td>
              <td><code><?= htmlspecialchars($p['reference']) ?></code></td>
              <td>
                <span class="statut-badge statut--<?= $p['statut'] ?>">
                  <?= $p['statut'] === 'confirme' ? '✅ Confirmé' : '⏳ En attente' ?>
                </span>
              </td>
              <td><?= isset($p['created_at']) ? date('d/m/Y H:i', strtotime($p['created_at'])) : '-' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>
