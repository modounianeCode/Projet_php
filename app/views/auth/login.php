<div class="auth-wrap">

  <div class="auth-card">
    <div class="auth-logo">🌾 <strong>Agro</strong>Market</div>
    <h2>Connexion</h2>
    <p class="auth-sub">Accédez à votre compte</p>

    <?php if (!empty($erreurs)): ?>
      <div class="alert alert--error">
        <?php foreach ($erreurs as $e): ?><p><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>auth/login" class="auth-form" novalidate>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required autofocus placeholder="votre@email.com">
      </div>

      <div class="form-group">
        <label>Mot de passe</label>
        <div class="input-pass">
          <input type="password" name="mot_de_passe" id="mdp" required placeholder="••••••••">
          <button type="button" onclick="toggleMdp()" tabindex="-1">👁</button>
        </div>
      </div>

      <button type="submit" class="btn btn--green btn--lg btn--block">Se connecter</button>
    </form>

    <p class="auth-switch">Pas encore de compte ?
      <a href="<?= BASE_URL ?>auth/inscription">S'inscrire</a>
    </p>

    <div class="demo-box">
      <p>🧪 Comptes de démo :</p>
      <code>vendeur@demo.com / 123456</code>
      <code>acheteur@demo.com / 123456</code>
    </div>
  </div>

  <div class="auth-visual">
    <h3>Bienvenue sur AgroMarket 🌾</h3>
    <p>La marketplace qui connecte agriculteurs et consommateurs au Sénégal.</p>
    <ul>
      <li>✅ Produits frais et locaux</li>
      <li>✅ Paiement Mobile Money</li>
      <li>✅ Livraison partout au Sénégal</li>
      <li>✅ Support vendeurs 7j/7</li>
    </ul>
  </div>

</div>

<script>
function toggleMdp() {
  const i = document.getElementById('mdp');
  i.type = i.type === 'password' ? 'text' : 'password';
}
</script>