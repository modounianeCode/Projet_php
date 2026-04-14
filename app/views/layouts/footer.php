</main>

<footer class="footer">
  <div class="footer__inner">

    <div class="footer__brand">
      <div class="footer__logo">🌾 <strong>Agro</strong>Market</div>
      <p>La marketplace qui connecte agriculteurs et consommateurs au Sénégal.</p>
      <div class="footer__pay">
        <span class="pay wave">Wave</span>
        <span class="pay orange">Orange Money</span>
        <span class="pay free">Free Money</span>
        <span class="pay cash">Cash</span>
      </div>
    </div>

    <div class="footer__col">
      <h4>Acheter</h4>
      <a href="<?= BASE_URL ?>produit/catalogue">Tous les produits</a>
      <a href="<?= BASE_URL ?>produit/catalogue?cat=Céréales">Céréales</a>
      <a href="<?= BASE_URL ?>produit/catalogue?cat=Légumes">Légumes</a>
      <a href="<?= BASE_URL ?>produit/catalogue?cat=Fruits">Fruits</a>
    </div>

    <div class="footer__col">
      <h4>Vendre</h4>
      <a href="<?= BASE_URL ?>auth/inscription">Devenir vendeur</a>
      <a href="<?= BASE_URL ?>produit/ajouter">Ajouter un produit</a>
      <a href="<?= BASE_URL ?>dashboard/vendeur">Mon dashboard</a>
    </div>

    <div class="footer__col">
      <h4>Zones livrées</h4>
      <a href="#">Dakar &amp; banlieue</a>
      <a href="#">Thiès · Mbour</a>
      <a href="#">Saint-Louis</a>
      <a href="#">Ziguinchor</a>
    </div>

  </div>
  <div class="footer__bottom">
    © <?= date('Y') ?> AgroMarket — Marketplace Agricole Intelligente | Projet PHP MVC – Équipe de 8
  </div>
</footer>

<script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>