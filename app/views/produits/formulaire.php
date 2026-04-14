<?php
$edit = !is_null($produit);
$titre = $edit ? 'Modifier le produit' : 'Ajouter un produit';
$action = $edit
  ? BASE_URL . 'produit/mettreAJour/' . $produit['id']
  : BASE_URL . 'produit/enregistrer';
?>

<div class="wrap wrap--narrow" style="padding-top:2rem">
  <h1><?= $titre ?></h1>
  <?php if (!$edit): ?>
    <p style="color:#757575;margin-bottom:1.5rem">Votre produit sera visible dans le catalogue après publication.</p>
  <?php endif; ?>

  <form method="POST" action="<?= $action ?>" enctype="multipart/form-data" class="form-card">

    <div class="form-row">
      <div class="form-group">
        <label>Nom du produit *</label>
        <input type="text" name="nom" required
               value="<?= htmlspecialchars($produit['nom'] ?? '') ?>"
               placeholder="Ex : Tomates fraîches Cayor">
      </div>
      <div class="form-group">
        <label>Catégorie *</label>
        <select name="categorie" required>
          <option value="">Choisir…</option>
          <?php foreach (['Céréales','Légumes','Fruits','Légumineuses','Tubercules','Transformés'] as $c): ?>
            <option value="<?= $c ?>" <?= ($produit['categorie'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label>Description *</label>
      <textarea name="description" rows="4" required
                placeholder="Qualité, provenance, mode de culture…"><?= htmlspecialchars($produit['description'] ?? '') ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Prix (FCFA) *</label>
        <input type="number" name="prix" required min="1"
               value="<?= $produit['prix'] ?? '' ?>" placeholder="500">
      </div>
      <div class="form-group">
        <label>Unité *</label>
        <select name="unite" required>
          <?php foreach (['kg','g','tonne','sac','litre','boite','paquet','unité'] as $u): ?>
            <option value="<?= $u ?>" <?= ($produit['unite'] ?? 'kg') === $u ? 'selected' : '' ?>><?= $u ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Stock *</label>
        <input type="number" name="stock" required min="0"
               value="<?= $produit['stock'] ?? '' ?>" placeholder="100">
      </div>
    </div>

    <div class="form-group">
      <label>Photo <?= $edit ? '(laisser vide pour garder l\'actuelle)' : '' ?></label>
      <?php if ($edit && !empty($produit['image']) && $produit['image'] !== 'default.svg'): ?>
        <img src="<?= UPLOAD_URL . htmlspecialchars($produit['image']) ?>"
             style="max-height:120px;border-radius:8px;margin-bottom:.5rem;display:block">
      <?php elseif ($edit): ?>
        <img src="<?= UPLOAD_URL ?>default.svg"
             style="max-height:120px;border-radius:8px;margin-bottom:.5rem;display:block">
      <?php endif; ?>
      <div class="upload-zone" id="upload-zone" onclick="document.getElementById('img-file').click()">
        <input type="file" name="image" id="img-file" accept="image/jpeg,image/png,image/webp" style="display:none">
        <div id="upload-hint"><span style="font-size:2rem">📸</span><p>Cliquez pour uploader</p><small>JPG · PNG · WebP — max 2 Mo</small></div>
        <img id="img-preview" src="" style="display:none;max-height:180px;border-radius:8px">
      </div>
    </div>

    <div class="form-actions">
      <a href="<?= BASE_URL ?>dashboard/vendeur" class="btn btn--outline">Annuler</a>
      <button type="submit" class="btn btn--green btn--lg">
        <?= $edit ? '💾 Enregistrer les modifications' : '🌾 Publier le produit' ?>
      </button>
    </div>
  </form>
</div>

<script>
document.getElementById('img-file').addEventListener('change', function () {
  if (!this.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('img-preview').src = e.target.result;
    document.getElementById('img-preview').style.display = 'block';
    document.getElementById('upload-hint').style.display  = 'none';
  };
  reader.readAsDataURL(this.files[0]);
});
</script>