<?php require_once('files/header.php');

$limit = 9; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Alapértelmezett rendezés
$orderBy = 'price ASC';
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'LowHighPrice';

// Nem és árintervallum kezelés
$selectedGender = isset($_GET['gender']) ? $_GET['gender'] : '';
$priceRange = isset($_GET['priceRange']) ? $_GET['priceRange'] : '';

// Rendezési paraméterek
switch ($sortOption) {
    case 'HighLowPrice':
        $orderBy = 'price DESC';
        break;
    case 'AZOrder':
        $orderBy = 'name ASC';
        break;
    case 'ZAOrder':
        $orderBy = 'name DESC';
        break;
}

// SQL lekérdezés generálása
$query = "SELECT * FROM products";
$conditions = [];

if ($selectedGender && $selectedGender != 'all') {
    $conditions[] = "gender = '$selectedGender'";
}

if ($priceRange) {
    switch ($priceRange) {
        case 'under5000':
            $conditions[] = "price < 5000";
            break;
        case '5000to10000':
            $conditions[] = "price BETWEEN 5000 AND 10000";
            break;
        case 'over10000':
            $conditions[] = "price > 10000";
            break;
    }
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY $orderBy LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('SQL Error: ' . mysqli_error($conn));
}

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

$total_query = "SELECT COUNT(*) AS total FROM products";
if (!empty($conditions)) {
    $total_query .= " WHERE " . implode(" AND ", $conditions);
}
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $limit); 
?>

<div class="page-title-overlap bg-linecolor pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="index.php"><i class="ci-home"></i>Főoldal</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Bolt</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">Bolt</h1>
        </div>
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <aside class="col-lg-4">
            <!-- Oldalsáv-->
            <div class="offcanvas offcanvas-collapse bg-white w-100 rounded-3 shadow-lg py-1" id="shop-sidebar" style="max-width: 22rem;">
              <div class="offcanvas-header align-items-center shadow-sm">
                <h2 class="h5 mb-0">Szűrők</h2>
                <button class="btn-close ms-auto" type="button" data-bs-dismiss="offcanvas" aria-label="Bezárás"></button>
              </div>
              <div class="offcanvas-body py-grid-gutter px-lg-grid-gutter">
                <!-- Kategóriák-->
                <div class="widget widget-categories mb-4 pb-4 border-bottom">
                  <h3 class="widget-title mb-5">Szűrő</h3>
                  <div class="accordion mt-n1" id="shop-categories">

                  <!-- Szűrés nem szerint-->
                  <div class="widget widget-filter mb-4 pb-4 border-bottom">
                      <h3 class="widget-title">Nem</h3>
                      <ul class="widget-list widget-filter-list list-unstyled pt-1" style="max-height: 11rem;" data-simplebar data-simplebar-auto-hide="false">
                          <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio"  name="gender" value="all" <?php if ($selectedGender == 'all' || $selectedGender == '') echo 'checked'; ?> onclick="filterByGender('all')">
                                  <label class="form-check-label widget-filter-item-text">Mind</label>
                              </div>
                          </li>
                          <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" id="gender-man" name="gender" value="man" <?php if ($selectedGender == 'man') echo 'checked'; ?> onclick="filterByGender('man')">
                                  <label class="form-check-label widget-filter-item-text" for="gender-man">Férfiak</label>
                              </div>
                          </li>
                          <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" id="gender-woman" name="gender" value="woman" <?php if ($selectedGender == 'woman') echo 'checked'; ?> onclick="filterByGender('woman')">
                                  <label class="form-check-label widget-filter-item-text" for="gender-woman">Nők</label>
                              </div>
                          </li>
                          <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
                              <div class="form-check">
                                  <input class="form-check-input" type="radio" id="gender-kid" name="gender" value="kid" <?php if ($selectedGender == 'kid') echo 'checked'; ?> onclick="filterByGender('kid')">
                                  <label class="form-check-label widget-filter-item-text" for="gender-kid">Gyerekek</label>
                              </div>
                          </li>
                      </ul>
                  </div>
                    <!-- Szűrés ár szerint -->
                    <div class="widget widget-filter mb-4 pb-4">
                        <h3 class="widget-title">Ár</h3>
                        <ul class="widget-list widget-filter-list list-unstyled pt-1" style="max-height: 11rem;" data-simplebar data-simplebar-auto-hide="false">
                            <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priceRange" value="all" <?php if ($priceRange == '' || $priceRange == 'all') echo 'checked'; ?> onclick="filterByPrice('all')">
                                    <label class="form-check-label widget-filter-item-text">Mind</label>
                                </div>
                            </li>
                            <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priceRange" value="under5000" <?php if ($priceRange == 'under5000') echo 'checked'; ?> onclick="filterByPrice('under5000')">
                                    <label class="form-check-label widget-filter-item-text">5 000 Ft alatt</label>
                                </div>
                            </li>
                            <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priceRange" value="5000to10000" <?php if ($priceRange == '5000to10000') echo 'checked'; ?> onclick="filterByPrice('5000to10000')">
                                    <label class="form-check-label widget-filter-item-text">5 000 Ft - 10 000 Ft</label>
                                </div>
                            </li>
                            <li class="widget-filter-item d-flex justify-content-between align-items-center mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priceRange" value="over10000" <?php if ($priceRange == 'over10000') echo 'checked'; ?> onclick="filterByPrice('over10000')">
                                    <label class="form-check-label widget-filter-item-text">10 000 Ft felett</label>
                                </div>
                            </li>
                        </ul>
                    </div>
              </div>
            </div>
          </aside>


        <!-- Content  -->
        <section class="col-lg-8">
    <!-- Eszköztár-->
    <div class="d-flex justify-content-center justify-content-sm-between align-items-center pt-2 pb-4 pb-sm-5">
        <div class="d-flex flex-wrap">
            <div class="d-flex align-items-center flex-nowrap me-3 me-sm-4 pb-3">
                <label class="text-light opacity-75 text-nowrap fs-sm me-2 d-none d-sm-block" for="sorting">Rendezés:</label>
                <select class="form-select" id="sorting" onchange="sortProducts()">
                    <option value="LowHighPrice" <?php if ($sortOption == 'LowHighPrice') echo 'selected'; ?>>Alacsony - Magas ár</option>
                    <option value="HighLowPrice" <?php if ($sortOption == 'HighLowPrice') echo 'selected'; ?>>Magas - Alacsony ár</option>
                    <option value="AZOrder" <?php if ($sortOption == 'AZOrder') echo 'selected'; ?>>A - Z sorrend</option>
                    <option value="ZAOrder" <?php if ($sortOption == 'ZAOrder') echo 'selected'; ?>>Z - A sorrend</option>
                </select><span class="fs-sm text-light opacity-75 text-nowrap ms-2 d-none d-md-block">a(z) <?php echo $total_products; ?> termékből</span>
            </div>
        </div>
        <div class="d-none d-sm-flex pb-3"><a class="btn btn-icon nav-link-style bg-light text-dark disabled opacity-100 me-2" href="#"><i class="ci-view-grid"></i></a></div>
    </div>
    <!-- Termékek rács-->
    <div class="row mx-n2">
        <!-- Termékek megjelenítése -->
        <div class="row mx-n2">
            <?php foreach ($products as $pro) {
                echo product_item_ui_1($pro);
            } ?>
        </div>
    </div>
    <!-- Banner-->
    <div class="py-sm-2">
        <div class="d-sm-flex justify-content-between align-items-center bg-secondary overflow-hidden mb-4 rounded-3">
            <div class="py-4 my-2 my-md-0 py-md-5 px-4 ms-md-3 text-center text-sm-start">
                <h4 class="fs-lg fw-light mb-2">Akció!</h4>
                <h3 class="mb-4">Tedd Kényelmessé a Napodat</h3><a class="btn btn-primary btn-shadow btn-sm" href="#">Vásárolj Most</a>
            </div>
        </div>
    </div>

    <hr class="my-3">
    <!-- Oldalelrendezés-->
    <nav class="d-flex justify-content-between pt-2" aria-label="Oldal navigáció">
      <ul class="pagination">
          <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
              <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>&gender=<?php echo urlencode($selectedGender); ?>&priceRange=<?php echo urlencode($priceRange); ?>&sort=<?php echo urlencode($sortOption); ?>">
                  <i class="ci-arrow-left me-2"></i>Előző
              </a>
          </li>
      </ul>
      <ul class="pagination">
          <li class="page-item d-sm-none"><span class="page-link page-link-static"><?php echo $page; ?> / <?php echo $total_pages; ?></span></li>
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                  <a class="page-link" href="?page=<?php echo $i; ?>&gender=<?php echo urlencode($selectedGender); ?>&priceRange=<?php echo urlencode($priceRange); ?>&sort=<?php echo urlencode($sortOption); ?>"><?php echo $i; ?></a>
              </li>
          <?php endfor; ?>
      </ul>
      <ul class="pagination">
          <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
              <a class="page-link" href="?page=<?php echo min($total_pages, $page + 1); ?>&gender=<?php echo urlencode($selectedGender); ?>&priceRange=<?php echo urlencode($priceRange); ?>&sort=<?php echo urlencode($sortOption); ?>" aria-label="Következő">
                  Következő<i class="ci-arrow-right ms-2"></i>
              </a>
          </li>
      </ul>
    </nav>
</section>
</div>
</div>
<script>
function filterByPrice(priceRange) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('priceRange', priceRange);
    urlParams.delete('page'); // Oldal újraindítása
    window.location.search = urlParams.toString();
}

function filterByGender(gender) {
    const urlParams = new URLSearchParams(window.location.search);
    const priceRange = urlParams.get('priceRange') || 'all';
    const sortOption = urlParams.get('sort') || 'LowHighPrice';

    urlParams.set('gender', gender);
    urlParams.set('priceRange', priceRange);
    urlParams.set('sort', sortOption);
    urlParams.delete('page'); // Oldal újraindítása
    window.location.search = urlParams.toString();
}

function sortProducts() {
    const selectedSort = document.getElementById('sorting').value;
    const urlParams = new URLSearchParams(window.location.search);

    urlParams.set('sort', selectedSort);
    urlParams.delete('page'); // Oldal újraindítása
    window.location.search = urlParams.toString();
}
</script>

<?php
require_once('files/footer.php');
?>
