<?php

$id = 0;
if (isset($_GET['id'])) {
    $id = ((int)($_GET["id"]));
}

if ($id <1) {
    die("ID nem található");
}
require_once('files/header.php');

$data = get_product($id);
$pro = $data["pro"];
$cat = $data["cat"];
if ($pro == null) {
    die("Termék nem található.");
}if($cat == null){
    die("Kategória nem található.");
}

$images = get_product_photos($pro["photos"]);


?>

     <!-- Oldal cím-->
<div class="page-title-overlap bg-linecolor pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="<?= BASE_URL?>"><i class="ci-home"></i>Főoldal</a></li>
                    <li class="breadcrumb-item text-nowrap"><a href="<?= BASE_URL?>/shop.php">Bolt</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page"><?= $pro["name"] ?></li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0"><?= $pro["name"]?></h1>
        </div>
    </div>
</div>
<div class="container">
    <!-- Galéria + részletek-->
    <div class="bg-light shadow-lg rounded-3 px-4 py-3 mb-5">
        <div class="px-lg-3">
            <div class="row">
                <!-- Termék galéria-->
                <div class="col-lg-7 pe-lg-0 pt-lg-4">
                    <div class="product-gallery">
                        <div class="product-gallery-preview order-sm-2">
                            <?php
                            foreach ($images as $key => $img) {
                                $active_class = $key === 0 ? "active" : "";
                                ?>
                                <div class="product-gallery-preview-item <?= $active_class ?>" id="pro-<?= $key ?>">
                                    <img class="image-zoom" src="<?= $img->thumb ?>" data-zoom="<?= $img->src ?>" alt="Termék kép">
                                    <div class="image-zoom-pane"></div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="product-gallery-thumblist order-sm-1">
                            <?php
                            $active_class = "active";
                            foreach ($images as $key => $img) { ?>

                                <a class="product-gallery-thumblist-item <?=  $active_class?>" href="#pro-<?= $key ?>">
                                    <img src="<?= $img->thumb?>" alt="Termék előnézet">
                                </a>

                                <?php  $active_class = "";
                            } ?>
                        </div>
                    </div>
                </div>

              <!-- Product details-->
              <div class="col-lg-5 pt-4 pt-lg-0">
    <div class="product-details ms-auto pb-3">
        <div class="d-flex justify-content-between align-items-center mb-2"><a href="#reviews" data-scroll>
                <div class="star-rating"><i class="star-rating-icon ci-star-filled active"></i><i class="star-rating-icon ci-star-filled active"></i><i class="star-rating-icon ci-star-filled active"></i><i class="star-rating-icon ci-star-filled active"></i><i class="star-rating-icon ci-star"></i>
                </div><span class="d-inline-block fs-sm text-body align-middle mt-1 ms-1">74 Értékelés</span></a>
            <form action="whislist-process-add.php" method="post">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button class="btn-wishlist me-0 me-lg-n3" type="submit" data-bs-toggle="tooltip" title="Hozzáadás a kívánságlistához"><i class="ci-heart"></i></button>
            </form>
        </div>
        <div class="mb-3"><span class="h3 fw-normal text-accent me-1"><?= number_format($pro["price"],2)?> Ft</span>
            <del class="text-muted fs-lg me-3"><?= number_format($pro["price"]*1.80,2)?> Ft</del><span class="badge bg-danger badge-shadow align-middle mt-n2">Akció</span>
        </div>

        <div class="position-relative me-n4 mb-3">
            <div class="product-badge product-available mt-n1"><i class="ci-security-check"></i>Termék elérhető</div>
        </div>
        <form action="cart-process-add.php" class="mb-grid-gutter" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center pb-1 mt-5">
                    <label class="form-label" for="product-size">Mennyiség:</label><a class="nav-link-style fs-sm" href="#size-chart" data-bs-toggle="modal"><i class="ci-ruler lead align-middle me-1 mt-n1"></i>Méret útmutató</a>
                </div>
            </div>
            <div class="mb-3 d-flex align-items-center">
                <select name="quantity" class="form-select me-3" style="width: 5rem;">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <button class="btn btn-primary btn-shadow d-block w-100" type="submit"><i class="ci-cart fs-lg me-2"></i>Hozzáadás a kosárhoz</button>
            </div>
        </form>
        <!-- Termék panelek-->
        <div class="accordion mb-4" id="productPanels">
            <div class="accordion-item">
                <h3 class="accordion-header"><a class="accordion-button" href="#productInfo" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="productInfo"><i class="ci-announcement text-muted fs-lg align-middle mt-n1 me-2"></i>Termék információ</a></h3>
                <div class="accordion-collapse collapse show" id="productInfo" data-bs-parent="#productPanels">
                    <div class="accordion-body">
                        <h6 class="fs-sm mb-2">Összetétel</h6>
                        <ul class="fs-sm ps-4">
                            <li>Rugalmas bordázat: 95% pamut, 5% elasztán</li>
                            <li>Bélés: 100% pamut</li>
                            <li>80% pamut, 20% poliészter</li>
                        </ul>
                        <h6 class="fs-sm mb-2">Cikkszám</h6>
                        <ul class="fs-sm ps-4 mb-0">
                            <li>183260098</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header"><a class="accordion-button collapsed" href="#shippingOptions" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="shippingOptions"><i class="ci-delivery text-muted lead align-middle mt-n1 me-2"></i>Szállítási lehetőségek</a></h3>
                <div class="accordion-collapse collapse" id="shippingOptions" data-bs-parent="#productPanels">
                    <div class="accordion-body fs-sm">
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <div>
                                <div class="fw-semibold text-dark">Futár</div>
                                <div class="fs-sm text-muted">2 - 4 nap</div>
                            </div>
                            <div>5000 Ft</div>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <div>
                                <div class="fw-semibold text-dark">Helyi szállítás</div>
                                <div class="fs-sm text-muted">legfeljebb egy hét</div>
                            </div>
                            <div>3000 Ft</div>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <div>
                                <div class="fw-semibold text-dark">Fix ár</div>
                                <div class="fs-sm text-muted">5 - 7 nap</div>
                            </div>
                            <div>10000 Ft</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Megosztás-->
            <label class="form-label d-inline-block align-middle my-2 me-3">Megosztás:</label><a class="btn-share btn-twitter me-2 my-2" href="#"><i class="ci-twitter"></i>Twitter</a><a class="btn-share btn-instagram me-2 my-2" href="#"><i class="ci-instagram"></i>Instagram</a><a class="btn-share btn-facebook my-2" href="#"><i class="ci-facebook"></i>Facebook</a>
        </div>
    </div>
</div>
            </div>
          </div>
        </div>
        <!-- Product description section 1-->
        <div class="row align-items-center py-md-3">
  <div class="col-lg-5 col-md-6 offset-lg-1 order-md-2"><img class="d-block rounded-3" src="img/shop/single/prod-img.jpg" alt="Kép"></div>
  <div class="col-lg-4 col-md-6 offset-lg-1 py-4 order-md-1">
    <h2 class="h3 mb-4 pb-2">Magas minőségű anyagok</h2>
    <h6 class="fs-base mb-3">Puhaszövet keverék</h6>
    <p class="fs-sm text-muted pb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit.</p>
    <h6 class="fs-base mb-3">Mosási utasítások</h6>
    <ul class="nav nav-tabs mb-3" role="tablist">
      <li class="nav-item"><a class="nav-link active" href="#wash" data-bs-toggle="tab" role="tab"><i class="ci-wash fs-xl"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="#bleach" data-bs-toggle="tab" role="tab"><i class="ci-bleach fs-xl"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="#hand-wash" data-bs-toggle="tab" role="tab"><i class="ci-hand-wash fs-xl"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="#ironing" data-bs-toggle="tab" role="tab"><i class="ci-ironing fs-xl"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="#dry-clean" data-bs-toggle="tab" role="tab"><i class="ci-dry-clean fs-xl"></i></a></li>
    </ul>
    <div class="tab-content text-muted fs-sm">
      <div class="tab-pane fade show active" id="wash" role="tabpanel">30° kímélő mosás gépben</div>
      <div class="tab-pane fade" id="bleach" role="tabpanel">Ne használjon fehérítőt</div>
      <div class="tab-pane fade" id="hand-wash" role="tabpanel">Kézi mosás normál (30°)</div>
      <div class="tab-pane fade" id="ironing" role="tabpanel">Alacsony hőmérsékletű vasalás</div>
      <div class="tab-pane fade" id="dry-clean" role="tabpanel">Ne vegytisztítsa</div>
    </div>
  </div>
</div>
<!-- Termék leírás szekció 2-->
<div class="row align-items-center py-4 py-lg-5">
  <div class="col-lg-5 col-md-6 offset-lg-1"><img class="d-block rounded-3" src="img/shop/single/prod-map.png" alt="Térkép"></div>
  <div class="col-lg-4 col-md-6 offset-lg-1 py-4">
    <h2 class="h3 mb-4 pb-2">Hol készült?</h2>
    <h6 class="fs-base mb-3">Ruhagyártó Kft.</h6>
    <p class="fs-sm text-muted pb-2">​Sam Tower, 6 Road No 32, Dhaka 1875, Banglades</p>
    <div class="d-flex mb-2">
      <div class="me-4 pe-2 text-center">
        <h4 class="h2 text-accent mb-1">3258</h4>
        <p>Dolgozó</p>
      </div>
      <div class="me-4 pe-2 text-center">
        <h4 class="h2 text-accent mb-1">43%</h4>
        <p>Női</p>
      </div>
      <div class="text-center">
        <h4 class="h2 text-accent mb-1">57%</h4>
        <p>Férfi</p>
      </div>
    </div>
    <h6 class="fs-base mb-3">Gyári információk</h6>
    <p class="fs-sm text-muted pb-md-2">​Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
  </div>
</div>
</div>
      <!-- Reviews-->
      <div class="border-top border-bottom my-lg-3 py-5">
  <div class="container pt-md-2" id="reviews">
    <div class="row pb-3">
      <div class="col-lg-4 col-md-5">
        <h2 class="h3 mb-4">74 Vélemény</h2>
        <div class="star-rating me-2"><i class="ci-star-filled fs-sm text-accent me-1"></i><i class="ci-star-filled fs-sm text-accent me-1"></i><i class="ci-star-filled fs-sm text-accent me-1"></i><i class="ci-star-filled fs-sm text-accent me-1"></i><i class="ci-star fs-sm text-muted me-1"></i></div><span class="d-inline-block align-middle">4.1 Összesített értékelés</span>
        <p class="pt-3 fs-sm text-muted">58 az 74-ből (77%)<br>A vásárlók ajánlják ezt a terméket</p>
      </div>
      <div class="col-lg-8 col-md-7">
        <div class="d-flex align-items-center mb-2">
          <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">5</span><i class="ci-star-filled fs-xs ms-1"></i></div>
          <div class="w-100">
            <div class="progress" style="height: 4px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div><span class="text-muted ms-3">43</span>
        </div>
        <div class="d-flex align-items-center mb-2">
          <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">4</span><i class="ci-star-filled fs-xs ms-1"></i></div>
          <div class="w-100">
            <div class="progress" style="height: 4px;">
              <div class="progress-bar" role="progressbar" style="width: 27%; background-color: #a7e453;" aria-valuenow="27" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div><span class="text-muted ms-3">16</span>
        </div>
        <div class="d-flex align-items-center mb-2">
          <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">3</span><i class="ci-star-filled fs-xs ms-1"></i></div>
          <div class="w-100">
            <div class="progress" style="height: 4px;">
              <div class="progress-bar" role="progressbar" style="width: 17%; background-color: #ffda75;" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div><span class="text-muted ms-3">9</span>
        </div>
        <div class="d-flex align-items-center mb-2">
          <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">2</span><i class="ci-star-filled fs-xs ms-1"></i></div>
          <div class="w-100">
            <div class="progress" style="height: 4px;">
              <div class="progress-bar" role="progressbar" style="width: 9%; background-color: #fea569;" aria-valuenow="9" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div><span class="text-muted ms-3">4</span>
        </div>
        <div class="d-flex align-items-center">
          <div class="text-nowrap me-3"><span class="d-inline-block align-middle text-muted">1</span><i class="ci-star-filled fs-xs ms-1"></i></div>
          <div class="w-100">
            <div class="progress" style="height: 4px;">
              <div class="progress-bar bg-danger" role="progressbar" style="width: 4%;" aria-valuenow="4" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div><span class="text-muted ms-3">2</span>
        </div>
      </div>
    </div>
  </div>
</div>
 
  <script src="vendor/lightgallery/plugins/video/lg-video.min.js"></script>
  <script src="vendor/lightgallery/lightgallery.min.js"></script>
  <?php
    require_once('files/footer.php');
  ?>

