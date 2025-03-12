<?php 
require_once('files/functions.php');
require_once('files/header.php');

if (!isset($_SESSION["wishlist"])) {
    $_SESSION["wishlist"] = [];
}

$wishlist_items = $_SESSION["wishlist"];
?>

<div class="page-title-overlap bg-linecolor pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index-2.html"><i class="ci-home"></i>Főoldal</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Kívánságlista</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Kívánságlistám</h1>
        </div>
        
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4 ">
    <div class="row">
        <!-- Fő Kívánságlista Szakasz -->
        <section class="col-lg-8 mt-5">

            <?php 
            if (empty($wishlist_items)) {
                echo '
                <div class="text-center py-5">
                    <img src="img/wishlist.png" alt="Üres kívánságlista" class="mb-4" style="max-width: 200px;">
                    <p class="text-muted mb-4">Még nem mentettél el semmilyen terméket. Nézd át a választékunkat, és add hozzá a kedvenceidet a kívánságlistádhoz!</p>
                    <a href="shop.php" class="btn btn-primary btn-lg">Vásárlás folytatása</a>
                </div>';
            } else {
                foreach ($wishlist_items as $key => $item) {
            ?>
            <!-- Kívánságlista Termék -->
            <div class="d-sm-flex justify-content-between align-items-center my-2 pb-3 border-bottom mt-5">
                <div class="d-block d-sm-flex align-items-center text-center text-sm-start">
                    <a class="d-inline-block flex-shrink-0 mx-auto me-sm-4" href="product.php?id=<?= $item["pro"]["id"] ?>">
                        <img src="<?= get_product_thumb($item["pro"]["photos"]) ?>" width="160" alt="Termék"></a>
                    <div class="pt-2">
                        <h3 class="product-title fs-base mb-2"><a href="product.php?id=<?= $item["pro"]["id"] ?>"><?= substr($item["pro"]["name"], 0, 1000) ?></a></h3>
                        <div class="fs-sm"><span class="text-muted me-2">ÁR: <?= number_format($item["pro"]["price"],2) ?> Ft</span></div>
                    </div>
                </div>
                <div class="pt-2 pt-sm-0 ps-sm-3 mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                    <a href="whislist-process-remove.php?id=<?= $item["pro"]["id"]?>" class="btn btn-link px-0 text-danger" type="button">
                        <i class="ci-close-circle me-2"></i><span class="fs-sm">Eltávolítás</span>
                    </a>
                </div>
            </div>
            <!-- Kívánságlista Termék -->
            <?php 
                } 
            }
            ?>
        </section>

        <!-- Oldalsáv -->
        <aside class="col-lg-4 pt-4 pt-lg-0 ps-xl-5">
            <div class="bg-white rounded-3 shadow-lg p-4">
                <h2 class="h6 mb-3">Kívánságlista Összegzés</h2>
                <div class="py-2">
                    <p class="text-muted">Összes termék: <?= count($wishlist_items) ?></p>
                </div>
                <div class="pt-2">
                    <a class="btn btn-primary d-block w-100" href="shop.php">Vásárlás folytatása</a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php require_once('files/footer.php'); ?>
