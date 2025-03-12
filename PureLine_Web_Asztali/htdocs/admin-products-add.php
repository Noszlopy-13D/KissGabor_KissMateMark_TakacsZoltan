<?php
require_once('files/functions.php');
protected_area();

// Kategóriák lekérdezése, ahol a parent_id nem nulla
$rows = db_select("categories", 'parent_id != 0');
$categories = [];

foreach ($rows as $val) {
    $categories[$val['id']] = $val['name'];
}

// "Nem" mezőhöz tartozó lehetőségek
$gender_options = [
    'Férfi' => 'Férfi',
    'Nő' => 'Nő',
    'Gyerek' => 'Gyerek',
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['form']['value'] = $_POST;

    $imgs = upload_images($_FILES);
    $data['name'] = $_POST['Név'];
    $data['buying_price'] = $_POST['buying_price'];
    $data['price'] = $_POST['price'];
    $data['category_id'] = $_POST['category_id'];
    $data['description'] = $_POST['description'];
    $data['photos'] = json_encode($imgs);
    $data['user_id'] = $_SESSION['user']['id'];
    $data['gender'] = $_POST['gender']; // A "nem" mező hozzáadva

    if (db_insert('products', $data)) {
        alert('success', 'Termék sikeresen létrehozva.');
        header('Location: admin-products.php');
        unset($_SESSION['form']);
    } else {
        alert('danger', 'Termék nem lett létrehozva, próbáld meg újra.');
        header('Location: admin-products-add.php');
    }
    die();
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
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Rendelések története</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Termék hozzáadása</h1>
        </div>
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <?php require_once('files/admin-account-sidebar.php'); ?>

        <!-- Tartalom -->
        <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 ps-lg-0 pe-xl-5">
                <!-- Cím -->
                <div class="d-sm-flex flex-wrap justify-content-between align-items-center pb-2">
                    <h2 class="h3 py-2 me-2 text-center text-sm-start mt-5">Új termék hozzáadása</h2>
                </div>
                <form action="admin-products-add.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 pb-2">
                        <div class="row">
                            <div class="col-md-12">
                                <?= text_input([
                                    'name' => 'Név',
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <?= select_input([
                                    'name' => 'category_id',
                                    'label' => 'Kategória',
                                ], $categories) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <?= text_input([
                                    'name' => 'buying_price',
                                    'label' => 'Beszerzési ár',
                                ]) ?>
                            </div>

                            <div class="col-md-6 mt-2">
                                <?= text_input([
                                    'name' => 'price',
                                    'label' => 'Eladási ár',
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <label for="gender">Nem</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">Válaszd ki a nemet</option>
                                    <?php foreach ($gender_options as $value => $label): ?>
                                        <option value="<?= $value ?>"><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="photo">Termékfotó 1</label>
                                    <input class="form-control" type="file" name="photo_1" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="photo">Termékfotó 2</label>
                                    <input class="form-control" type="file" name="photo_2" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="photo">Termékfotó 3</label>
                                    <input class="form-control" type="file" name="photo_3" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="photo">Termékfotó 4</label>
                                    <input class="form-control" type="file" name="photo_4" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="photo">Termékfotó 5</label>
                                    <input class="form-control" type="file" name="photo_5" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <label for="photo">Termékfotó 6</label>
                                    <input class="form-control" type="file" name="photo_6" accept=".jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Leírás</label>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary d-block w-100" type="submit"><i class="ci-cloud-upload fs-lg me-2"></i>BEKÜLDÉS</button>
                </form>
            </div>
        </section>
    </div>
</div>

<?php
require_once('files/footer.php');
?>
