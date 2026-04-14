<div class="wrap wrap--wide" style="padding-top:2rem">

  <div class="dash-head">
    <div>
      <h1>🛒 Mon Espace Acheteur</h1>
      <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['user_nom']) ?></strong> !</p>
    </div>
    <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--green">Faire des achats</a>
  </div>

  <!-- Stats -->
  <div class="stats">
    <div class="stat stat--green">
      <div class="stat__ico">🛒</div>
      <div class="stat__val"><?= count($commandes) ?></div>
      <div class="stat__label">Commandes passées</div>
    </div>
    <div class="stat stat--blue">
      <div class="stat__ico">🚚</div>
      <div class="stat__val"><?= count(array_filter($livraisons, fn($l) => $l['statut'] === 'en_cours')) ?></div>
      <div class="stat__label">Livraisons en cours</div>
    </div>
    <div class="stat stat--orange">
      <div class="stat__ico">💰</div>
      <div class="stat__val"><?= number_format(array_sum(array_column($commandes, 'total')), 0, ',', ' ') ?></div>
      <div class="stat__label">FCFA total dépensé</div>
    </div>
    <div class="stat stat--green">
      <div class="stat__ico">✅</div>
      <div class="stat__val"><?= count(array_filter($commandes, fn($c) => $c['statut'] === 'livree')) ?></div>
      <div class="stat__label">Commandes livrées</div>
    </div>
  </div>

  <!-- Mes commandes -->
  <div class="dash-section">
    <h2>📋 Mes Commandes</h2>

    <?php if (empty($commandes)): ?>
      <div class="empty">
        <p>Vous n'avez pas encore commandé.</p>
        <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--green">Découvrir les produits</a>
      </div>
    <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr><th>#</th><th>Articles</th><th>Total</th><th>Zone</th><th>Statut</th><th>Date</th><th>Détails</th></tr>
        </thead>
        <tbody>
          <?php foreach ($commandes as $c): ?>
          <tr>
            <td><strong>#<?= $c['id'] ?></strong></td>
            <td><?= $c['nb_articles'] ?> article<?= $c['nb_articles'] > 1 ? 's' : '' ?></td>
            <td><strong><?= number_format($c['total'], 0, ',', ' ') ?> FCFA</strong></td>
            <td><?= htmlspecialchars($c['zone_livraison'] ?? '—') ?></td>
            <td><span class="statut-badge statut--<?= $c['statut'] ?>"><?= $c['statut'] ?></span></td>
            <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
            <td>
              <a href="<?= BASE_URL ?>commande/confirmation/<?= $c['id'] ?>"
                 class="btn btn--outline btn--sm">Voir →</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <!-- Suivi livraisons -->
  <?php if (!empty($livraisons)): ?>
  <div class="dash-section">
    <h2>🚚 Suivi des Livraisons</h2>
    <div class="livraisons">
      <?php foreach ($livraisons as $l): ?>
      <div class="livraison-card">
        <div class="livraison-card__head">
          <strong>Commande #<?= $l['commande_id'] ?></strong>
          <span class="statut-badge statut--<?= $l['statut'] ?>"><?= $l['statut'] ?></span>
        </div>

        <!-- Barre de progression -->
        <?php
        $etapes = ['en_attente' => 0, 'en_cours' => 1, 'livree' => 2];
        $idx    = $etapes[$l['statut']] ?? 0;
        $labels = ['Traitement', 'Expédié', 'Livré'];
        ?>
        <div class="livraison-progress">
          <?php foreach ($labels as $i => $lab): ?>
            <div class="lp-step <?= $i <= $idx ? 'lp-step--done' : '' ?>">
              <div class="lp-dot"></div>
              <div class="lp-label"><?= $lab ?></div>
            </div>
            <?php if ($i < count($labels) - 1): ?>
              <div class="lp-line <?= $i < $idx ? 'lp-line--done' : '' ?>"></div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <div class="livraison-card__info">
          <span>Zone : <strong><?= htmlspecialchars($l['zone']) ?></strong></span>
          <span>Prévue le : <strong><?= date('d/m/Y', strtotime($l['date_prevue'])) ?></strong></span>
          <span>Frais : <strong><?= number_format($l['frais'], 0, ',', ' ') ?> FCFA</strong></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</div>