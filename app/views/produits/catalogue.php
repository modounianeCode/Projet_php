<div class="wrap wrap--wide" style="padding-top:2rem">

  <div class="page-head">
    <h1>🛒 Catalogue</h1>
    <p><?= $total ?> produit<?= $total > 1 ? 's' : '' ?> disponible<?= $total > 1 ? 's' : '' ?></p>
  </div>

  <div class="catalogue-layout">

    <!-- ── FILTRES ────────────────────────────────── -->
    <aside class="filtres">
      <h3>🔍 Filtrer</h3>
      <form method="GET" action="<?= BASE_URL ?>produit/catalogue">

        <div class="f-group">
          <label>Recherche</label>
          <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Nom, description…">
        </div>

        <div class="f-group">
          <label>Catégorie</label>
          <select name="cat">
            <option value="">Toutes</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= $c ?>" <?= $cat === $c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="f-group">
          <label>Prix min (FCFA)</label>
          <input type="number" name="pMin" value="<?= $pMin > 0 ? $pMin : '' ?>" placeholder="0" min="0">
        </div>

        <div class="f-group">
          <label>Prix max (FCFA)</label>
          <input type="number" name="pMax" value="<?= $pMax < 999999 ? $pMax : '' ?>" placeholder="Illimité" min="0">
        </div>

        <button type="submit" class="btn btn--green btn--block">Appliquer</button>
        <?php if ($q || $cat || $pMin > 0 || $pMax < 999999): ?>
          <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--outline btn--block" style="margin-top:.5rem">Réinitialiser</a>
        <?php endif; ?>
      </form>

      <!-- Raccourcis catégories -->
      <div style="margin-top:1.5rem">
        <h4 style="font-size:.85rem;color:#757575;margin-bottom:.6rem">Catégories</h4>
        <?php
        $ico = ['Céréales'=>'🌾','Légumes'=>'🥬','Fruits'=>'🥭',
                'Légumineuses'=>'🫘','Tubercules'=>'🥔','Transformés'=>'🫙'];
        foreach ($categories as $c): ?>
        <a href="<?= BASE_URL ?>produit/catalogue?cat=<?= urlencode($c) ?>"
           class="cat-pill <?= $cat === $c ? 'cat-pill--active' : '' ?>">
          <?= $ico[$c] ?? '🌿' ?> <?= $c ?>
        </a>
        <?php endforeach; ?>
      </div>
    </aside>

    <!-- ── GRILLE ─────────────────────────────────── -->
    <section class="produits-zone">

      <?php if (empty($produits)): ?>
        <div class="empty">
          <div style="font-size:4rem">🔍</div>
          <h3>Aucun produit trouvé</h3>
          <a href="<?= BASE_URL ?>produit/catalogue" class="btn btn--green">Voir tout le catalogue</a>
        </div>
      <?php else: ?>

        <div class="grid-produits">
          <?php foreach ($produits as $p): ?>
            <?php include __DIR__ . '/_card.php'; ?>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($nbPages > 1): ?>
        <nav class="pagination">
          <?php if ($page > 1): ?>
            <a href="?page=<?= $page-1 ?>&q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>" class="pag-btn">‹</a>
          <?php endif; ?>
          <?php for ($i = max(1,$page-2); $i <= min($nbPages,$page+2); $i++): ?>
            <a href="?page=<?= $i ?>&q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>"
               class="pag-btn <?= $i === $page ? 'pag-btn--active' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>
          <?php if ($page < $nbPages): ?>
            <a href="?page=<?= $page+1 ?>&q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>" class="pag-btn">›</a>
          <?php endif; ?>
        </nav>
        <?php endif; ?>

      <?php endif; ?>
    </section>

  </div>
</div>