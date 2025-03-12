<?php
require_once('files/functions.php');

if (isset($_POST["last_name"])) {
 $_SESSION["shipping"]["first_name"] = $_POST["first_name"];
 $_SESSION["shipping"]["last_name"] = $_POST["last_name"];
 $_SESSION["shipping"]["address"] = $_POST["address"];
 $_SESSION["shipping"]["phone_number"] = $_POST["phone_number"];
}

if(!isset($_SESSION['user'])){
    alert('warning','Kérlek, regisztrálj vagy jelentkezz be a folytatáshoz.');
    header('Location: login.php');
    die();
}

require_once('files/header.php');
$cart_items = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];
$_SESSION["cart"];
if(isset($_SESSION["cart"])){
    $cart_items = $_SESSION["cart"];
}

$u = $_SESSION['user'];

?>
<!-- Oldal címe -->
<div class="page-title-overlap bg-linecolor pt-4">
        <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
          <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                <li class="breadcrumb-item"><a class="text-nowrap" href="index.php"><i class="ci-home"></i>Főoldal</a></li>
                <li class="breadcrumb-item text-nowrap"><a href="shop.php">Bolt</a></li>
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
            
            <form action="submit-order.php" method="post">
              <!-- Lépések -->
            <div class="steps steps-light pt-2 pb-3 mb-5"><a class="step-item active" href="cart.php">
                <div class="step-progress"><span class="step-count">1</span></div>
                <div class="step-label"><i class="ci-cart"></i>Kosár</div>
            </a><a class="step-item active " href="checkout.php">
                <div class="step-progress"><span class="step-count">2</span></div>
                <div class="step-label"><i class="ci-user-circle"></i>Pénztár</div>
            </a><a class="step-item active current" href="review.php">
                <div class="step-progress"><span class="step-count">3</span></div>
                <div class="step-label"><i class="ci-check-circle"></i>Áttekintés</div>
            </a>
        </div>

            <!-- Szállítási cím -->
            <h2 class="h6 pt-1 pb-3 mb-3 border-bottom">Ellenőrizd a rendelésed</h2>
            <?php 
            foreach ($cart_items as $key => $item) {
            ?>
            <!-- Termék -->
            <div class="d-sm-flex justify-content-between align-items-center my-2 pb-3 border-bottom">
              <div class="d-block d-sm-flex align-items-center text-center text-sm-start"><a class="d-inline-block flex-shrink-0 mx-auto me-sm-4" href="product.php?id=<?= $item["pro"]["id"]?>">
                <img src="<?=get_product_thumb($item["pro"]["photos"])?>" width="160" alt="Termék"></a>
                <div class="pt-2">
                  <h3 class="product-title fs-base mb-2"><a href="product.php?id=<?= $item["pro"]["id"]?>"><?=substr($item["pro"]["name"],0,1000)?></a></h3>
                  <div class="fs-sm"><span class="text-muted me-2">EGYÉNI ÁR: <?=number_format($item["pro"]["price"], 2)?> Ft</span></div> 
                  <div class="fs-lg text-accent pt-2"><?=number_format($item["pro"]["price"] * $item["quantity"],2)?> Ft</div>
                </div>
              </div>
              <div class="pt-2 pt-sm-0 ps-sm-3 mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                <label class="form-label" for="quantity1">Mennyiség</label>
                <input class="form-control" type="number" readonly id="quantity1" min="1" value="<?=$item["quantity"]?>">
              </div>
            </div>
            <!-- Termék -->
            <?php } ?>

            <!-- Navigáció (asztali) -->
            <div class="d-none d-lg-flex pt-4 mt-3">
              <div class="w-50 pe-3"><a class="btn btn-secondary d-block w-100" href="checkout.php"><i class="ci-arrow-left mt-sm-0 me-1"></i><span class="d-none d-sm-inline">Vissza a pénztárhoz</span><span class="d-inline d-sm-none">Vissza</span></a></div>
              <div class="w-50 ps-2"><button type="submit" class="btn btn-primary d-block w-100" href="review.php"><span class="d-none d-sm-inline">Rendelés leadása</span><span class="d-inline d-sm-none">Következő</span><i class="ci-arrow-right mt-sm-0 ms-1"></i></button></div>
            </div>
            

          </section>
          <!-- Oldalsáv -->
          <aside class="col-lg-4 pt-4 pt-lg-0 ps-xl-5">
            <div class="bg-white rounded-3 shadow-lg p-4 ms-lg-auto">
              <div class="py-2 px-xl-2">
                <div class="widget mb-3">
                  <h2 class="widget-title text-center">Rendelés összegzése</h2>

                <ul class="list-unstyled fs-sm pb-2 border-bottom">
                  <li class="d-flex justify-content-between align-items-center"><span class="me-2">Szállítás:</span><span class="text-end">—</span></li>
                  <li class="d-flex justify-content-between align-items-center"><span class="me-2">Összesen:</span><span class="text-end"><?= number_format($cart_total,2)?> Ft</span></li>
                </ul>
                <h3 class="fw-normal text-center my-4"><?=number_format( $cart_total,2)?> Ft</h3>

              </div>
            </div>
          </aside>
        </div>
        <!-- Navigáció (mobil) -->
        <div class="row d-lg-none">
          <div class="col-lg-8">
            <div class="d-flex pt-4 mt-3">
              <div class="w-50 pe-3"><a class="btn btn-secondary d-block w-100" href="checkout.php"><i class="ci-arrow-left mt-sm-0 me-1"></i><span class="d-none d-sm-inline">Vissza a pénztárhoz</span><span class="d-inline d-sm-none">Vissza</span></a></div>
              <div class="w-50 ps-2"><button type="submit" class="btn btn-primary d-block w-100" href="review.php"><span class="d-none d-sm-inline">Rendelés leadása</span><span class="d-inline d-sm-none">Következő</span><i class="ci-arrow-right mt-sm-0 ms-1"></i></button></div>
            </div>
          </div>
        </div>
      </div>
      </form>

<?php
    require_once('files/footer.php');
?>
