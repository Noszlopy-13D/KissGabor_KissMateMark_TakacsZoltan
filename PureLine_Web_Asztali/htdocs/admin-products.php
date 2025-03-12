<?php
require_once('files/functions.php');
protected_area();

// Termékek lekérése
$products = db_select("products", '1 ORDER BY id DESC');

// Megrendelések lekérése
$orders = db_select("orders", '1'); // Igény szerint állítsa be az aktuális lekérdezést

// Értékesítések és bevételek inicializálása
$salesData = []; // Az értékesítési számok tárolására
$totalEarnings = []; // A bevételi összegek tárolására

// Értékesítési és bevételi tömbök inicializálása minden termékhez
foreach ($products as $product) {
    $productId = $product['id'];
    $salesData[$productId] = 0; // Az értékesítési szám inicializálása ehhez a termékhez
    $totalEarnings[$productId] = 0; // A bevétel inicializálása ehhez a termékhez
}

// Minden rendelés feldolgozása
foreach ($orders as $order) {
    $cartData = json_decode($order['cart'], true); // Feltételezzük, hogy a kosár JSON karakterlánc

    foreach ($cartData as $item) {
        $productId = $item['pro']['id']; // A termék ID-jának lekérése a kosár elemből
        $quantity = $item['quantity']; // A mennyiség lekérése a kosár elemből

        // Ellenőrizzük, hogy a termék ID-ja egyezik-e
        if (isset($salesData[$productId])) {
            // Az értékesítési szám növelése
            $salesData[$productId] += $quantity;

            // A termék árának lekérése a termék ID-ja alapján
            $productPrice = 0; // Alapértelmezett ár
            foreach ($products as $product) {
                if ($product['id'] == $productId) {
                    $productPrice = $product['price'];
                    break; // A ciklus kilépése, amint a termék megtalálható
                }
            }

            // Bevétel számítása (ár * mennyiség)
            $totalEarnings[$productId] += $productPrice * $quantity;
        }
    }
}

require_once('files/header.php');
?>

<div class="page-title-overlap bg-linecolor pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index.php"><i class="ci-home"></i>Főoldal</a></li>
                    <li class="breadcrumb-item text-nowrap"><a href="#">Fiók</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Admin Termékek</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Termékek</h1>
        </div>
    </div>
</div>
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">

        <?php require_once('files/admin-account-sidebar.php'); ?>

        <!-- Tartalom -->
        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 ps-lg-0 pe-xl-5">

                <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom mt-5">
                    <h2 class="h3 py-2 me-2 text-center text-sm-start">Termékeid<span class="badge bg-faded-accent fs-sm text-body align-middle ms-2"><?= count($products) ?></span></h2>
                </div>

                <?php
                foreach ($products as $pro) {
                    $productId = $pro['id'];
                    $salesCount = $salesData[$productId]; // Az értékesítési szám lekérése a termékhez
                    $earnings = $totalEarnings[$productId]; // A bevétel lekérése a termékhez
                ?>
                <!-- Termék -->
                <div class="d-block d-sm-flex align-items-center py-4 border-bottom">
                    <a class="d-block mb-3 mb-sm-0 me-sm-4 ms-sm-0 mx-auto" href="shop.php" style="width: 12.5rem;">
                        <img class="rounded-3" src="<?= get_product_thumb($pro['photos']) ?>" alt="Termék"></a>
                    <div class="text-center text-sm-start">
                        <h3 class="h6 product-title mb-2"><a href="shop.php"><?= $pro["name"] ?></a></h3>
                        <div class="d-inline-block text-accent"><?= number_format($pro["price"], 2) ?> Ft</div>
                        <div class="d-inline-block text-accent fs-ms border-start ms-2 ps-2">Értékesítések: <span class="fw-medium"><?= $salesCount ?></span></div>
                        <div class="d-inline-block text-accent fs-ms border-start ms-2 ps-2">Bevételek: <span class="fw-medium"><?= number_format($earnings, 2) ?> Ft</span></div>
                        <div class="d-flex justify-content-center justify-content-sm-start pt-3">
                            <button class="btn bg-faded-info btn-icon me-2" type="button" data-bs-toggle="tooltip" title="Szerkesztés" data-id="<?= $pro['id'] ?>" onclick="editProduct(this)"><i class="ci-edit text-info"></i></button>
                            <button class="btn bg-faded-danger btn-icon" type="button" data-bs-toggle="tooltip" title="Törlés" data-id="<?= $pro['id'] ?>" onclick="deleteProduct(this)"><i class="ci-trash text-danger"></i></button>
                        </div>
                    </div>
                </div>
                <?php } ?>

            </div>
        </section>
    </div>
</div>
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Termék szerkesztése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="product-id">
                    <div class="mb-3">
                        <label for="product-name" class="form-label">Termék neve</label>
                        <input type="text" class="form-control" id="product-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-price" class="form-label">Ár</label>
                        <input type="number" class="form-control" id="product-price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="product-photo" class="form-label">Termék fénykép</label>
                        <input type="file" class="form-control" id="product-photo" name="photo">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégsem</button>
                <button type="button" class="btn btn-primary" onclick="submitEditProductForm()">Módosítások mentése</button>
            </div>
        </div>
    </div>
</div>

<script>
function editProduct(button) {
    const productId = button.getAttribute('data-id');
    const currentName = button.closest('.d-block').querySelector('.product-title a').textContent;
    const currentPrice = button.closest('.d-block').querySelector('.text-accent').textContent.trim();

    // Töltsük be az aktuális adatokat a modal mezőibe
    document.getElementById('product-id').value = productId;
    document.getElementById('product-name').value = currentName;
    document.getElementById('product-price').value = currentPrice;

    // Megnyitjuk a modalt
    const editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
    editModal.show();
}

function submitEditProductForm() {
    const form = document.getElementById('editProductForm');
    const formData = new FormData(form);

    // AJAX kérés küldése a szerverre
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "edit_product.php", true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            alert("A termék sikeresen frissítve.");
            location.reload();
        } else {
            alert("Hiba a termék frissítése során: " + xhr.responseText);
        }
    };

    xhr.send(formData);
}

function deleteProduct(button) {
    const productId = button.getAttribute('data-id');
    
    if (confirm("Biztosan törölni szeretné ezt a terméket?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_product.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Sikeres törlés, frissítjük az oldalt
                location.reload();
            } else {
                alert("Hiba a termék törlése során: " + xhr.responseText);
            }
        };

        xhr.send("id=" + productId);
    }
}
</script>

<?php
require_once('files/footer.php');
?>
