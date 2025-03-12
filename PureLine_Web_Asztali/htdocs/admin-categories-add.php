<?php
require_once('files/functions.php');
protected_area();

$categories[0] = "Nincs szülő";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $_SESSION['form']['value'] = $_POST;

  $imgs = upload_images($_FILES);
  $data['name'] = $_POST['Név'];
  $data['description'] = $_POST['description'];

  // A szülő ID automatikus beállítása az utolsó kategória alapján
  $lastCategory = db_select("categories", null, 'id DESC', 1); // Lekérdezi az utolsónak hozzáadott kategóriát
  $data['parent_id'] = (isset($lastCategory[0]['id']) ? $lastCategory[0]['id'] + 1 : 1); // Szülő ID növelése

  $data['photo'] = json_encode($imgs);

  if (db_insert('categories', $data)) {
    alert('success', 'A kategória sikeresen létrehozva.');
    header('Location: admin-categories.php');
    unset($_SESSION['form']);
  } else {
    alert('danger', 'A kategória nem lett létrehozva, próbáld meg újra.');
    header('Location: admin-categories-add.php');
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
          <li class="breadcrumb-item text-nowrap active" aria-current="page">Rendelések kategóriája</li>
        </ol>
      </nav>
    </div>
    <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
      <h1 class="h3 text-light mb-0">Termékkategória létrehozása</h1>
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
          <h2 class="h3 py-2 me-2 text-center text-sm-start mt-5">Új kategória hozzáadása</h2>
        </div>
        <form action="admin-categories-add.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3 pb-2">
            <?= text_input(['name' => 'Név']) ?>
            <div class="row mt-4">
              
              <div class="col-12">
                <div class="form-group">
                  <label for="photo">Kategória képe</label>
                  <input class="form-control" type="file" name="photo" accept=".jpg,.jpeg,.png">
                </div>
              </div>
              <div class="mb-3 pb-2 mt-2">
                <div class="col-12">
                  <div class="form-group">
                    <label for="description">Leírás</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <button class="btn btn-primary d-block w-100" type="submit"><i class="ci-cloud-upload fs-lg me-2"></i>Küldés</button>
        </form>
      </div>
    </section>
  </div>
</div>

<?php
require_once('files/footer.php');
?>
