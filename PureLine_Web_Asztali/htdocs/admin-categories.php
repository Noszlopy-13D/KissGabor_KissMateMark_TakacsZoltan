<?php
require_once('files/functions.php');
protected_area();

// Kategóriák lekérdezése
$categories = db_select("categories", '1 ORDER BY id DESC');

// Termékek lekérdezése
$products = db_select("products", '1'); // Szükség esetén módosítsd a lekérdezést

// Rendelések lekérdezése
$orders = db_select("orders", '1'); // Szükség esetén módosítsd a lekérdezést

// Kategória eladások és bevételek tömbök inicializálása
$categoryProductCount = [];
$categoryEarnings = [];

// Kategória adatok inicializálása
foreach ($categories as $category) {
    $categoryId = $category['id'];
    $categoryProductCount[$categoryId] = 0; // Eladás szám inicializálása ehhez a kategóriához
    $categoryEarnings[$categoryId] = 0; // Bevételek inicializálása ehhez a kategóriához
}

// Minden rendelés feldolgozása
foreach ($orders as $order) {
    $cartData = json_decode($order['cart'], true); // Feltételezve, hogy a cart JSON formátumú

    foreach ($cartData as $item) {
        $productId = $item['pro']['id']; // Termék ID a kosár elemből
        $quantity = $item['quantity']; // Mennyiség a kosár elemből

        // Termékár keresése termék ID alapján
        $productPrice = 0; // Alapértelmezett ár
        foreach ($products as $product) {
            if ($product['id'] == $productId) {
                $productPrice = $product['price'];
                $categoryId = $product['category_id']; // Feltételezve, hogy van category_id a termékeknél
                break; // Kilépés a ciklusból, ha megtaláltuk a terméket
            }
        }

        // Bevételek számítása és eladás szám növelése, ha a kategória ID érvényes
        if (isset($categoryEarnings[$categoryId])) {
            $categoryProductCount[$categoryId] += $quantity;
            $categoryEarnings[$categoryId] += $productPrice * $quantity;
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
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index.php"><i class="ci-home"></i>Kezdőlap</a></li>
                    <li class="breadcrumb-item text-nowrap"><a href="#">Fiók</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Termékkategóriák</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Termékkategóriák</h1>
        </div>
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <?php require_once('files/admin-account-sidebar.php'); ?>

        <!-- Tartalom  -->
        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
                <!-- Cím -->
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center border-bottom">
                    <h2 class="h3 py-2 me-2 text-center text-sm-start mt-5">Kategóriáid</h2>
                </div>

                <?php
                foreach ($categories as $category) {
                    $categoryId = $category['id'];
                    $name = htmlspecialchars($category['name']);
                    $photoData = json_decode($category['photo'], true);
                    $photo = isset($photoData[0]['src']) ? htmlspecialchars($photoData[0]['src']) : '';

                    // Eladás szám és bevétel lekérése ehhez a kategóriához
                    $salesCount = isset($categoryProductCount[$categoryId]) ? $categoryProductCount[$categoryId] : 0;
                    $earnings = isset($categoryEarnings[$categoryId]) ? $categoryEarnings[$categoryId] : 0;
                    $formattedEarnings = number_format($earnings, 2); // Formázott bevételek

                    echo <<<EOF
                    <div class="d-block d-sm-flex align-items-center py-4 border-bottom">
                        <a class="d-block mb-3 mb-sm-0 me-sm-4 ms-sm-0 mx-auto" style="width: 12.5rem;">
                            <img class="rounded-3" src="$photo" alt="$name">
                        </a>
                        <div class="text-center text-sm-start">
                            <h3 class="h6 product-title mb-2">$name</h3>
                            <div class="d-inline-block text-accent">Eladások: <span class="fw-medium">$salesCount</span></div>
                            <div class="d-inline-block text-accent ms-2">Bevételek: <span class="fw-medium">$formattedEarnings Ft</span></div>
                            <div class="d-flex justify-content-center justify-content-sm-start pt-3">
                                <button class="btn bg-faded-info btn-icon me-2" type="button" data-bs-toggle="tooltip" title="Szerkesztés" data-id="$categoryId" onclick="editCategory(this)"><i class="ci-edit text-info"></i></button>
                                <button class="btn bg-faded-danger btn-icon" type="button" data-bs-toggle="tooltip" title="Törlés" data-id="$categoryId" onclick="deleteCategory(this)"><i class="ci-trash text-danger"></i></button>
                            </div>
                        </div>
                    </div>
                    EOF;
                }
                ?>
            </div>
        </section>
    </div>
</div>

<!-- Kategória szerkesztése modális ablak -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Kategória szerkesztése</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="category-id">
                    <div class="mb-3">
                        <label for="category-name" class="form-label">Kategória neve</label>
                        <input type="text" class="form-control" id="category-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category-photo" class="form-label">Kategória képe</label>
                        <input type="file" class="form-control" id="category-photo" name="photo">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                <button type="button" class="btn btn-primary" onclick="submitEditCategoryForm()">Változtatások mentése</button>
            </div>
        </div>
    </div>
</div>

<script>
function editCategory(button) {
    const categoryId = button.getAttribute('data-id');
    const categoryName = button.closest('.d-block').querySelector('.product-title').textContent;

    // Az aktuális adatok kitöltése a modális mezőkbe
    document.getElementById('category-id').value = categoryId;
    document.getElementById('category-name').value = categoryName;

    // Modális megnyitása
    const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
    editModal.show();
}

function submitEditCategoryForm() {
    const form = document.getElementById('editCategoryForm');
    const formData = new FormData(form);

    // AJAX kérés a szerver felé
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "edit_category.php", true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            alert("A kategória sikeresen frissítve.");
            location.reload();
        } else {
            alert("Hiba a kategória frissítése során: " + xhr.responseText);
        }
    };

    xhr.send(formData);
}

function deleteCategory(button) {
    const categoryId = button.getAttribute('data-id');

    if (confirm("Biztosan törölni szeretnéd ezt a kategóriát?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_category.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            } else {
                alert("Hiba a kategória törlése során: " + xhr.responseText);
            }
        };

        xhr.send("id=" + categoryId);
    }
}
</script>

<?php
require_once('files/footer.php');
?>
