<div class="wrap wrap--wide" style="padding-top:2rem">

  <div class="dash-head">
    <div>
      <h1>🌾 Dashboard Vendeur</h1>
      <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['user_nom']) ?></strong> !</p>
    </div>
    <a href="<?= BASE_URL ?>produit/ajouter" class="btn btn--green">+ Nouveau produit</a>
  </div>

  <!-- Statistiques -->
  <div class="stats">
    <div class="stat stat--green">
      <div class="stat__ico">📦</div>
      <div class="stat__val"><?= count($produits) ?></div>
      <div class="stat__label">Produits</div>
    </div>
    <div class="stat stat--blue">
      <div class="stat__ico">🛒</div>
      <div class="stat__val"><?= count($commandes) ?></div>
      <div class="stat__label">Commandes reçues</div>
    </div>
    <div class="stat stat--orange">
      <div class="stat__ico">💰</div>
      <div class="stat__val"><?= number_format($ca, 0, ',', ' ') ?></div>
      <div class="stat__label">FCFA chiffre d'affaires</div>
    </div>
    <div class="stat stat--red">
      <div class="stat__ico">⚠️</div>
      <div class="stat__val"><?= count($stats['stock_faible']) ?></div>
      <div class="stat__label">Stock faible (&lt; 10)</div>
    </div>
  </div>

  <!-- Alerte stock -->
  <?php if (!empty($stockFaible)): ?>
  <div class="alert alert--warning" style="margin-bottom:1.5rem">
    ⚠️ <strong>Stock faible :</strong>
    <?php foreach ($stockFaible as $sp): ?>
      <span class="tag-rouge"><?= htmlspecialchars($sp['nom']) ?> (<?= $sp['stock'] ?>)</span>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Mes produits -->
  <div class="dash-section">
    <div class="dash-section__head">
      <h2>📦 Mes Produits</h2>
      <a href="<?= BASE_URL ?>produit/ajouter" class="btn btn--green btn--sm">+ Ajouter</a>
    </div>

    <?php if (empty($produits)): ?>
      <div class="empty">
        <p>Aucun produit. <a href="<?= BASE_URL ?>produit/ajouter">Ajouter votre premier produit →</a></p>
      </div>
    <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr><th>Image</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($produits as $p): ?>
          <tr>
            <td>
              <?php if (!empty($p['image']) && $p['image'] !== 'default.png'): ?>
                <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>" class="table-thumb">
              <?php else: ?>
                <div class="table-thumb-ph">🌿</div>
              <?php endif; ?>
            </td>
            <td><strong><?= htmlspecialchars($p['nom']) ?></strong></td>
            <td><span class="tag"><?= htmlspecialchars($p['categorie']) ?></span></td>
            <td><?= number_format($p['prix'], 0, ',', ' ') ?> FCFA/<?= $p['unite'] ?></td>
            <td>
              <span class="stock-ind <?= $p['stock'] < 10 ? 'stock-ind--low' : 'stock-ind--ok' ?>">
                <?= $p['stock'] ?>
              </span>
            </td>
            <td class="actions">
              <a href="<?= BASE_URL ?>produit/modifier/<?= $p['id'] ?>" class="btn btn--outline btn--sm">✏️</a>
              <a href="<?= BASE_URL ?>produit/detail/<?= $p['id'] ?>" class="btn btn--outline btn--sm">👁</a>
              <a href="<?= BASE_URL ?>produit/supprimer/<?= $p['id'] ?>" class="btn btn--danger btn--sm"
                 onclick="return confirm('Supprimer « <?= htmlspecialchars($p['nom']) ?> » ?')">🗑</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <!-- Commandes reçues -->
  <div class="dash-section">
    <h2>🛒 Commandes Reçues</h2>

    <?php if (empty($commandes)): ?>
      <div class="empty"><p>Aucune commande pour le moment.</p></div>
    <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr><th>#</th><th>Acheteur</th><th>Total</th><th>Zone</th><th>Statut</th><th>Date</th><th>Changer statut</th></tr>
        </thead>
        <tbody>
          <?php foreach ($commandes as $c): ?>
          <tr>
            <td>#<?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['acheteur_nom']) ?></td>
            <td><strong><?= number_format($c['total'], 0, ',', ' ') ?> FCFA</strong></td>
            <td><?= htmlspecialchars($c['zone_livraison'] ?? '—') ?></td>
            <td><span class="statut-badge statut--<?= $c['statut'] ?>"><?= $c['statut'] ?></span></td>
            <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
            <td>
              <form action="<?= BASE_URL ?>dashboard/changerStatut/<?= $c['id'] ?>"
                    method="POST" style="display:flex;gap:.4rem;align-items:center">
                <select name="statut" class="select-sm">
                  <?php foreach (['en_attente','payee','en_livraison','livree','annulee'] as $s): ?>
                    <option value="<?= $s ?>" <?= $c['statut'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn btn--green btn--sm">✓</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

</div>