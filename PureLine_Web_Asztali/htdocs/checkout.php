<?php
require_once('files/functions.php');

if(!isset($_SESSION['user'])){
    alert('warning','Regisztrálj vagy jelentkezz be a folytatáshoz.');
    header('Location: login.php');
    die();
}

require_once('files/header.php');

$cart_items = [];
$_SESSION["cart"];
if(isset($_SESSION["cart"])){
    $cart_items = $_SESSION["cart"];
}

$u = $_SESSION['user'];
?>

      <!-- Oldalcím -->
      <div class="page-title-overlap bg-linecolor pt-4">
        <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
          <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                <li class="breadcrumb-item"><a class="text-nowrap" href="index.php"><i class="ci-home"></i>Kezdőlap</a></li>
                <li class="breadcrumb-item text-nowrap"><a href="shop.php">Bolt</a>
                </li>
                <li class="breadcrumb-item text-nowrap active" aria-current="page">Pénztár</li>
              </ol>
            </nav>
          </div>
          <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Pénztár</h1>
          </div>
        </div>
      </div>
      <div class="container pb-5 mb-2 mb-md-4">
        <div class="row">
          <section class="col-lg-8">
            
            <form action="review.php" method="post">
              <!-- Lépések -->
            <div class="steps steps-light pt-2 pb-3 mb-5"><a class="step-item active" href="cart.php">
                <div class="step-progress"><span class="step-count">1</span></div>
                <div class="step-label"><i class="ci-cart"></i>Kosár</div>
            </a><a class="step-item active current" href="checkout.php">
                <div class="step-progress"><span class="step-count">2</span></div>
                <div class="step-label"><i class="ci-user-circle"></i>Pénztár</div>
            </a><a class="step-item" href="review.php">
                <div class="step-progress"><span class="step-count">3</span></div>
                <div class="step-label"><i class="ci-check-circle"></i>Áttekintés</div>
            </a>
        </div>
            <!-- Felhasználói információ -->
            <div class="d-sm-flex justify-content-between align-items-center bg-secondary p-4 rounded-3 mb-grid-gutter">
              <div class="d-flex align-items-center">
                <div class="img-thumbnail rounded-circle position-relative flex-shrink-0"><span class="badge bg-warning position-absolute end-0 mt-n2" data-bs-toggle="tooltip" title="Id"><?=$u["id"] ?></span><img class="rounded-circle" src="img/user.png" width="90" alt="Felhasználó"></div>
                <div class="ps-3">
                  <h3 class="fs-base mb-0"><?=$u["last_name"]." ".$u["first_name"] ?></h3><span class="text-accent fs-sm"><?=$u["email"]?></span>
                </div>
              </div>
            </div>
            <!-- Szállítási cím -->
            <h2 class="h6 pt-1 pb-3 mb-3 border-bottom">Szállítási cím</h2>
            <div class="row">
              <div class="col-sm-6">
                <div class="mb-3">

                    <?= text_input([ 
                        "name" => "first_name",
                        "label" => "Keresztnév",
                        "value" => $u["first_name"],
                        "attributes" => "required"

                    ]) ?>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                <?= text_input([
                        "name" => "last_name",
                        "label" => "Vezetéknév",
                        "attributes" => "required",
                        "value" => $u["last_name"],
                    ]) ?>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="mb-3">
                <?= text_input([
                        "name" => "phone_number",
                        "label" => "Telefonszám",
                        "attributes" => "required",

                    ]) ?>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="mb-3">
                <?= text_input([
                        "name" => "address",
                        "label" => "Cím",
                        "attributes" => "required",

                    ]) ?>
                </div>
              </div>
            </div>

            <h6 class="mb-3 py-3 border-bottom">Számlázási cím</h6>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" checked id="same-address">
              <label class="form-check-label" for="same-address">Ugyanaz, mint a szállítási cím</label>
            </div>

            <!-- Navigáció (asztali) -->
            <div class="d-none d-lg-flex pt-4 mt-3">
              <div class="w-50 pe-3"><a class="btn btn-secondary d-block w-100" href="cart.php"><i class="ci-arrow-left mt-sm-0 me-1"></i><span class="d-none d-sm-inline">Vissza a kosárhoz</span><span class="d-inline d-sm-none">Vissza</span></a></div>
              <div class="w-50 ps-2"><button type="submit" class="btn btn-primary d-block w-100" href="review.php"><span class="d-none d-sm-inline">Tovább a szállításhoz</span><span class="d-inline d-sm-none">Tovább</span><i class="ci-arrow-right mt-sm-0 ms-1"></i></button></div>
            </div>
            </form>

          </section>
          <!-- Oldalsáv -->
          <aside class="col-lg-4 pt-4 pt-lg-0 ps-xl-5">
            <div class="bg-white rounded-3 shadow-lg p-4 ms-lg-auto">
              <div class="py-2 px-xl-2">
                <div class="widget mb-3">
                  <h2 class="widget-title text-center">Rendelés összesítése</h2>
                  <div class="widget widget-cart px-3 pt-2 pb-3" style="width: 20rem;">
                    <div style="height: 15rem;" data-simplebar data-simplebar-auto-hide="false">
                      <?php foreach ($cart_items as $key => $item) { ?>
                        <div class="d-flex align-items-center pb-2 border-bottom">
                          <a class="d-block flex-shrink-0" href="product.php?id=<?= $item["pro"]["id"] ?>">
                            <img src="<?= get_product_thumb($item["pro"]["photos"]) ?>" width="64" alt="Termék">
                          </a>
                          <div class="ps-2">
                            <h6 class="widget-product-title">
                              <a href="product.php?id=<?= $item["pro"]["id"] ?>"><?= htmlspecialchars($item["pro"]["name"]) ?></a>
                            </h6>
                            <div class="widget-product-meta">
                              <span class="text-accent me-2"><?= number_format($item["pro"]["price"]) ?> Ft</span>
                              <span class="text-muted">x <?= $item["quantity"] ?></span>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                  

                </div>
                <ul class="list-unstyled fs-sm pb-2 border-bottom">
                  <li class="d-flex justify-content-between align-items-center"><span class="me-2">Szállítás:</span><span class="text-end">—</span></li>
                  <li class="d-flex justify-content-between align-items-center"><span class="me-2">Összesen:</span><span class="text-end"><?= number_format($cart_total,2)?> Ft</span></li>
                </ul>
                <h3 class="fw-normal text-center my-4"><?= number_format($cart_total,2)?> Ft</h3>
                <form class="needs-validation" method="post" novalidate>
                  <div class="mb-3">
                    <input class="form-control" type="text" placeholder="Promóciós kód" required>
                    <div class="invalid-feedback">Kérlek, add meg a promóciós kódot.</div>
                  </div>
                  <button class="btn btn-outline-primary d-block w-100" type="submit">Promóciós kód alkalmazása</button>
                </form>
              </div>
            </div>
          </aside>
        </div>
        <!-- Navigáció (mobil) -->
        <div class="row d-lg-none">
          <div class="col-lg-8">
            <div class="d-flex pt-4 mt-3">
              <div class="w-50 pe-3"><a class="btn btn-secondary d-block w-100" href="cart.php"><i class="ci-arrow-left mt-sm-0 me-1"></i><span class="d-none d-sm-inline">Vissza a kosárhoz</span><span class="d-inline d-sm-none">Vissza</span></a></div>
              <div class="w-50 ps-2"><a class="btn btn-primary d-block w-100" href="review.php"><span class="d-none d-sm-inline">Tovább a szállításhoz</span><span class="d-inline d-sm-none">Tovább</span><i class="ci-arrow-right mt-sm-0 ms-1"></i></a></div>
            </div>
          </div>
        </div>
      </div>

<?php
    require_once('files/footer.php');
?>
