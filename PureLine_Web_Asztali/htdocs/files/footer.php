</main>
<!-- Footer-->
<footer class="footer pt-5">
  <div class="pt-5 bg-linecolor">
    <div class="container">
      <!-- Három elem középre igazítva -->
      <div class="row justify-content-center text-center pb-3">
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="d-flex flex-column align-items-center">
            <i class="ci-rocket text-primary" style="font-size: 2.25rem;"></i>
            <div class="pt-3">
              <h6 class="fs-base text-light mb-1">Gyors és ingyenes szállítás</h6>
              <p class="mb-0 fs-ms text-light opacity-50">Ingyenes szállítás minden 10 000 forint feletti rendelésre</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="d-flex flex-column align-items-center">
            <i class="ci-currency-exchange text-primary" style="font-size: 2.25rem;"></i>
            <div class="pt-3">
              <h6 class="fs-base text-light mb-1">Pénzvisszafizetési garancia</h6>
              <p class="mb-0 fs-ms text-light opacity-50">A pénzt 30 napon belül visszatérítjük</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
          <div class="d-flex flex-column align-items-center">
            <i class="ci-support text-primary" style="font-size: 2.25rem;"></i>
            <div class="pt-3">
              <h6 class="fs-base text-light mb-1">24/7 ügyfélszolgálat</h6>
              <p class="mb-0 fs-ms text-light opacity-50">Barátságos ügyfélszolgálat 24/7</p>
            </div>
          </div>
        </div>
      </div>
      <hr class="hr-light mb-5">
      <!-- Szerzői jog középre igazítva -->
      <div class="pb-4 fs-xs text-light opacity-50 text-center">
        © Minden jog fenntartva. Készítette: <a class="text-light" href="" target="_blank" rel="noopener">PureLine</a>
      </div>
    </div>
  </div>
</footer>
<!-- Eszköztár (Alapértelmezett)-->
<div class="handheld-toolbar">
  <div class="d-table table-layout-fixed w-100">
    <a class="d-table-cell handheld-toolbar-item" href="wishlist.php">
      <span class="handheld-toolbar-icon">
        <i class="ci-heart"></i>
        <span class="badge bg-primary rounded-pill ms-1"><?=$wishlist_count ?></span>
      </span>
      <span class="handheld-toolbar-label">Kívánságlista</span>
    </a>
    <a class="d-table-cell handheld-toolbar-item" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" onclick="window.scrollTo(0, 0)">
      <span class="handheld-toolbar-icon"><i class="ci-menu"></i></span>
      <span class="handheld-toolbar-label">Menü</span>
    </a>
    <a class="d-table-cell handheld-toolbar-item" href="cart.php">
      <span class="handheld-toolbar-icon">
        <i class="ci-cart"></i>
        <span class="badge bg-primary rounded-pill ms-1"><?=$cart_count ?></span>
      </span>
      <span class="handheld-toolbar-label"><?=number_format($cart_total, 2) ?> Ft</span>
    </a>
  </div>
</div>

<!-- Vissza a tetejére gomb-->
<a class="btn-scroll-top" href="#top" data-scroll
   style="right: 32px; bottom: 100px;">
    <span class="btn-scroll-top-tooltip text-muted fs-sm me-2">Fel</span>
    <i class="btn-scroll-top-icon ci-arrow-up"></i>
</a>


<!-- Vendor script: js könyvtárak és pluginok-->
<script src="vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="vendor/simplebar/dist/simplebar.min.js"></script>
<script src="vendor/tiny-slider/dist/min/tiny-slider.js"></script>
<script src="vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
<script src="vendor/drift-zoom/dist/Drift.min.js"></script>
<script src="vendor/card/dist/card.js"></script>

<!-- Fő téma script-->
<script src="js/theme.min.js"></script>

</body>
</html>
