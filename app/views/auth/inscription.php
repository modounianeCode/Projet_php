<div class="auth-wrap auth-wrap--wide">

  <div class="auth-card auth-card--wide">
    <div class="auth-logo">🌾 <strong>Agro</strong>Market</div>
    <h2>Créer un compte</h2>
    <p class="auth-sub">Rejoignez la marketplace agricole</p>

    <?php if (!empty($erreurs)): ?>
      <div class="alert alert--error">
        <?php foreach ($erreurs as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>auth/inscription" class="auth-form" novalidate>

      <!-- Choix du rôle -->
      <div class="role-selector">
        <label class="role-opt">
          <input type="radio" name="role" value="acheteur"
            <?= ($ancien['role'] ?? 'acheteur') === 'acheteur' ? 'checked' : '' ?>>
          <div class="role-card js-role-card">
            <span>🛒</span>
            <strong>Acheteur</strong>
            <small>Acheter des produits</small>
          </div>
        </label>
        <label class="role-opt">
          <input type="radio" name="role" value="vendeur"
            <?= ($ancien['role'] ?? '') === 'vendeur' ? 'checked' : '' ?>>
          <div class="role-card js-role-card">
            <span>🌾</span>
            <strong>Vendeur</strong>
            <small>Vendre mes récoltes</small>
          </div>
        </label>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Nom complet *</label>
          <input type="text" name="nom" required
                 value="<?= htmlspecialchars($ancien['nom'] ?? '') ?>"
                 placeholder="Mamadou Diallo">
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" name="email" required
                 value="<?= htmlspecialchars($ancien['email'] ?? '') ?>"
                 placeholder="mamadou@email.com">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Mot de passe * (6 car. min.)</label>
          <div class="input-pass">
            <input type="password" name="mot_de_passe" id="mdp" required
                   minlength="6" placeholder="••••••••">
            <button type="button" onclick="toggleMdp()" tabindex="-1">👁</button>
          </div>
        </div>
        <div class="form-group">
          <label>Téléphone</label>
          <input type="tel" name="telephone"
                 value="<?= htmlspecialchars($ancien['telephone'] ?? '') ?>"
                 placeholder="+221 77 123 45 67">
        </div>
      </div>

      <div class="form-group">
        <label>Adresse / Localisation</label>
        <input type="text" name="adresse"
               value="<?= htmlspecialchars($ancien['adresse'] ?? '') ?>"
               placeholder="Dakar, Plateau…">
      </div>

      <button type="submit" class="btn btn--green btn--lg btn--block">Créer mon compte 🚀</button>
    </form>

    <p class="auth-switch">Déjà inscrit ?
      <a href="<?= BASE_URL ?>auth/login">Se connecter</a>
    </p>
  </div>

</div>

<script>
function toggleMdp() {
  const i = document.getElementById('mdp');
  i.type = i.type === 'password' ? 'text' : 'password';
}
// Highlight rôle sélectionné
document.querySelectorAll('input[name="role"]').forEach(radio => {
  const highlight = () => {
    document.querySelectorAll('.js-role-card').forEach(c => c.classList.remove('role-card--active'));
    radio.closest('.role-opt').querySelector('.js-role-card').classList.add('role-card--active');
  };
  radio.addEventListener('change', highlight);
  if (radio.checked) highlight();
});
</script>