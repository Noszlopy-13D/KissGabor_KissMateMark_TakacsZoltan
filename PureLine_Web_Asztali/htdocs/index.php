<?php
require_once 'files/header.php';

$products = db_select("products", '1 ORDER BY id DESC');
// fake_products_generator();
?>

<!-- Hero slider -->
<section class="tns-carousel tns-controls-lg">
    <div class="tns-carousel-inner" data-carousel-options='{"mode": "gallery", "responsive": {"0":{"nav":true, "controls": false}, "992":{"nav":false, "controls": true}}}'>
        
        <?php for ($i = 0; $i < 3; $i++): ?>
            <div class="px-lg-5" style="background-color: #eed4bd;">
                <div class="d-lg-flex justify-content-center align-items-center text-center flex-column flex-lg-row">
                    
                    <!-- Kép reszponzívan középen -->
                    <div class="order-lg-2">
                        <img class="img-fluid d-block mx-auto" style="max-width: 100%; max-height: 500px; height: auto; object-fit: contain;" 
                             src="img/nyari.avif" alt="Nyári kollekció">
                    </div>

                    <div class="position-relative mx-auto py-5 px-4 text-center text-lg-start" style="max-width: 42rem; z-index: 10;">
                        <h3 class="h2 text-color<?= $i ?> fw-light pb-1 from-start">Most érkezett meg!</h3>
                        <h2 class="text-color<?= $i ?> display-5 from-start delay-1">Óriási Nyári Kollekció</h2>
                        <p class="fs-lg text-color<?= $i ?> pb-3 from-start delay-2">
                            Fürdőruhák, Felsők, Rövidnadrágok, Napszemüvegek és még sok más...
                        </p>
                        <div class="d-table scale-up delay-4 mx-auto mx-lg-0">
                            <a class="btn btn-primary" href="shop.php">Vásárolj most<i class="ci-arrow-right ms-2 me-n1"></i></a>
                            <a class="btn btn-secondary d-table mt-4 mx-auto mx-lg-0" href="uploads/PureLine.pdf" target="_blank">
                                Webshop dokumentáció megnyitása
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        <?php endfor; ?>
    </div>
</section>


<!-- Products grid (Trending products) -->
<section class="container pt-md-3 pb-5 mb-md-3" style="margin-top: 100px;">
    <h2 class="h3 text-center mt-50">Népszerű termékek</h2>
    <div class="row mx-n2 mt-5">
        <?php
        shuffle($products);
        $random_products = array_slice($products, 0, 6);
        foreach ($random_products as $pro) {
            echo product_item_ui_1($pro);
        }
        ?>
    </div>
    <div class="text-center pt-3">
        <a class="btn btn-outline-accent" href="shop.php">Több termék<i class="ci-arrow-right ms-1"></i></a>
    </div>
</section>

<!-- Kiemelt kategória -->
<section class="container mb-4 pb-3 pb-sm-0 mb-sm-5">
    <div class="row">
        <div class="col-md-5">
            <div class="d-flex flex-column h-100 overflow-hidden rounded-3" style="background-color: #e2e9ef;">
                <div class="d-flex justify-content-between px-grid-gutter py-grid-gutter">
                    <div>
                        <h3 class="mb-1">Különleges nap</h3>
                        <a class="fs-md" href="shop.php">Vásárolj most!<i class="ci-arrow-right fs-xs align-middle ms-1"></i></a>
                    </div>
                    <div class="tns-carousel-controls" id="hoodie-day">
                        <button type="button"><i class="ci-arrow-left"></i></button>
                        <button type="button"><i class="ci-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-7 pt-4 pt-md-0">
            <div class="tns-carousel">
                <div class="tns-carousel-inner" data-carousel-options='{"nav": false, "controlsContainer": "#hoodie-day"}'>
                    <?php for ($i = 0; $i < 2; $i++): ?>
                        <div>
                            <div class="row mx-n2">
                                <?php
                                $count = 0;
                                foreach ($products as $pro) {
                                    if ($count < 6) {
                                        echo product_item_ui_static($pro);
                                        $count++;
                                    } else {
                                        break;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog + Instagram info cards -->
<section class="container-fluid px-0">
    <div class="row g-0">
        <div class="col-md-6">
            <a class="card border-0 rounded-0 text-decoration-none py-md-4 bg-faded-primary" href="#">
                <div class="card-body text-center">
                    <i class="ci-edit h3 mt-2 mb-4 text-primary"></i>
                    <h3 class="h5 mb-1">Olvasd a blogot</h3>
                    <p class="text-muted fs-sm">Legfrissebb bolt, divat hírek és trendek</p>
                </div>
            </a>
        </div>
        <div class="col-md-6">
            <a class="card border-0 rounded-0 text-decoration-none py-md-4 bg-faded-accent" href="#">
                <div class="card-body text-center">
                    <i class="ci-instagram h3 mt-2 mb-4 text-accent"></i>
                    <h3 class="h5 mb-1">Kövess minket az Instagramon</h3>
                    <p class="text-muted fs-sm">#PureLine</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Start of Tawk.to Script -->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/672b34184304e3196addd0ed/1ic0c70h2';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!-- End of Tawk.to Script -->

<?php require_once 'files/footer.php'; ?>
