<?php
require_once('files/functions.php');
require_once('files/header.php');

// A kosár munkamenet inicializálása, ha még nincs beállítva
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

?>

<div class="page-title-overlap bg-linecolor pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item">
                        <a class="text-nowrap" href="index.php"><i class="ci-home"></i>Főoldal</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap">
                        <a href="shop.php">Bolt</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Kosár</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Kosaram</h1>
        </div>
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <!-- Tételek listája -->
        <section class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-4 pb-sm-5 mt-1">
                <!-- Jövőbeli elemek helye -->
            </div>

            <?php 
            // Ellenőrizzük, hogy a kosár üres-e
            if (empty($cart_items)) {
                echo '
                <div class="text-center py-5">
                    <img src="img/cart.png" alt="Üres kosár" class="mb-4" style="max-width: 200px;">
                    <p class="text-muted mb-4">Úgy tűnik, még nem adtál hozzá semmit a kosaradhoz. Böngéssz a termékeink között, és kezdj el vásárolni!</p>
                    <a href="shop.php" class="btn btn-primary btn-lg">Folytatás a vásárláshoz</a>
                </div>';
            } else {
                // Végigmegyünk a kosár tételein és megjelenítjük azokat
                foreach ($cart_items as $key => $item) {
            ?>
                <!-- Tétel -->
                <div class="d-sm-flex justify-content-between align-items-center my-2 pb-3 border-bottom mt-5">
                    <div class="d-block d-sm-flex align-items-center text-center text-sm-start">
                        <a class="d-inline-block flex-shrink-0 mx-auto me-sm-4" href="product.php?id=<?= $item["pro"]["id"] ?>">
                            <img src="<?= get_product_thumb($item["pro"]["photos"]) ?>" width="160" alt="Termék">
                        </a>
                        <div class="pt-2">
                            <h3 class="product-title fs-base mb-2">
                                <a href="product.php?id=<?= $item["pro"]["id"] ?>"><?= substr($item["pro"]["name"], 0, 1000) ?></a>
                            </h3>
                            <div class="fs-sm"><span class="text-muted me-2">EGYSÉGÁR: <?= number_format($item["pro"]["price"],2) ?> Ft</span></div>
                            <div class="fs-lg text-accent pt-2"><?= number_format($item["pro"]["price"] * $item["quantity"],2) ?> Ft</div>
                        </div>
                    </div>
                    <div class="pt-2 pt-sm-0 ps-sm-3 mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                        <label class="form-label" for="quantity<?= $key ?>">Mennyiség</label>
                        <input class="form-control" type="number" readonly id="quantity<?= $key ?>" min="1" value="<?= $item["quantity"] ?>">
                        <a href="cart-process-remove.php?id=<?= $item["pro"]["id"] ?>" class="btn btn-link px-0 text-danger" type="button">
                            <i class="ci-close-circle me-2"></i><span class="fs-sm">Eltávolítás</span>
                        </a>
                    </div>
                </div>
                <!-- Tétel vége -->
            <?php 
                }
            }
            ?>
        </section>

<!-- Oldalsáv -->
<aside class="col-lg-4 pt-4 pt-lg-0 ps-xl-5">
    <div class="bg-white rounded-3 shadow-lg p-4">
        <div class="py-2 px-xl-2">
            <div class="text-center mb-4 pb-3 border-bottom">
                <h2 class="h6 mb-3">Kosár összegzés</h2>
                <p class="text-muted">Összes tétel: <?= count($cart_items) ?></p>
            </div>

            <div class="accordion" id="order-options">
                <div class="text-center bg-white rounded-3 shadow-lg p-4">
                    <h2 class="h6 mb-3 pb-1">Részösszeg</h2>
                    <div class="py-2">
                        <h3 class="fw-normal"><?= number_format($cart_total,2) ?> Ft</h3>
                    </div>
                </div>
            </div>

            <?php if (!empty($cart_items)) { ?>
                <!-- Megjelenítjük a pénztárazási gombot, ha a kosár nem üres -->
                <a class="btn btn-primary btn-shadow d-block w-100 mt-4" href="checkout.php">
                    <i class="ci-card fs-lg me-2"></i>Tovább a pénztárhoz
                </a>
            <?php } else { ?>
                <!-- Megjelenítjük a letiltott gombot és üzenetet, ha a kosár üres -->
                <a class="btn btn-secondary btn-shadow d-block w-100 mt-4 disabled" href="#">
                    <i class="ci-card fs-lg me-2"></i>Tovább a pénztárhoz
                </a>
                <p class="text-danger text-center mt-3">A kosarad üres. Kérlek, adj hozzá tételeket a pénztárazás előtt.</p>
            <?php } ?>
        </div>
    </div>
</aside>

<?php
require_once('files/footer.php');
?>
