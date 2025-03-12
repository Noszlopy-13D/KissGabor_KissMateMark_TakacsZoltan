<?php
require_once('files/functions.php');

$cart_count = 0;
$cart_items = [];
$cart_total = 0;
$my_cart_counter = 0;
if (isset($_SESSION['cart'])){
  if(is_array($_SESSION["cart"])){

    foreach ($_SESSION["cart"] as $key => $item) {
      $cart_total += $item["pro"]["price"]*$item["quantity"];
      $my_cart_counter++;
      if ($my_cart_counter > 5) {
        continue;
      }
      $cart_items[] = $item;
    }
    $cart_count = count($_SESSION['cart']);
  
  }
}

// Kívánságlista
$wishlist_count = 0; 
$wishlist_items = [];
$my_wishlist_counter = 0;

if (isset($_SESSION['wishlist']) && is_array($_SESSION["wishlist"])) {
    foreach ($_SESSION["wishlist"] as $key => $item) {
        $my_wishlist_counter++;
        if ($my_wishlist_counter > 5) {
            continue;
        }
        $wishlist_items[] = $item;
    }
    $wishlist_count = count($_SESSION['wishlist']);
}
?>

<!DOCTYPE html>
<html lang="hu">
  
<head>
<meta charset="UTF-8">
    <title>PureLine Divat Webshop</title>
      <!-- SEO Meta Tag-ek-->
      <meta name="description" content="PureLine – Az elegáns és minimalista divat webshop, ahol a tiszta vonalak találkoznak a modern stílussal.">
      <meta name="keywords" content="PureLine, divat, webshop, minimalista, elegáns, ruházat, női divat, férfi divat, fenntartható, modern, online vásárlás">
      <meta name="author" content="PureLine Webshop">
    <!-- Viewport-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon és Érintő Ikonok-->
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="mask-icon" color="#fe6a6a" href="safari-pinned-tab.svg">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendor Stílusok: Font Ikonok, Pluginok, stb.-->
    <link rel="stylesheet" media="screen" href="vendor/simplebar/dist/simplebar.min.css"/>
    <link rel="stylesheet" media="screen" href="vendor/tiny-slider/dist/tiny-slider.css"/>
    <link rel="stylesheet" media="screen" href="vendor/drift-zoom/dist/drift-basic.min.css"/>
    <!-- Fő Téma Stílusok + Bootstrap-->
    <link rel="stylesheet" media="screen" href="css/theme.min.css">
    <style>
      .bg-linecolor{
        background-color:#4a344f;
      }
      /* Alapértelmezett táblázat nagyobb képernyőkön */
      .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      /* Mobilos kártya nézet */
      @media (max-width: 768px) {
        table,
        thead,
        tbody,
        th,
        td,
        tr {
          display: block;
        }

        thead tr {
          display: none;
        }

        tr {
          margin-bottom: 15px;
          border-bottom: 1px solid #ddd;
        }

        td {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 10px;
          border-bottom: 1px solid #ddd;
          font-size: 14px;
        }

        td::before {
          content: attr(data-label);
          font-weight: bold;
          text-transform: uppercase;
          margin-right: 10px;
        }
      }
      .text-color{
        color: rgb(91 14 14) !important;
      }
      .text-color1{
        color: rgb(145 87 7) !important;
      }
      .text-color2{
        color: rgb(97 50 93) !important;
      }
    </style>

</head>
  <!-- Body-->
  <body class="handheld-toolbar-enabled">

 <!-- Belépés / Regisztráció Modal -->
<div class="modal fade" id="signin-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-secondary">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link fw-medium active" href="#signin-tab" data-bs-toggle="tab" role="tab" aria-selected="true">
              <i class="ci-unlocked me-2 mt-n1"></i>Bejelentkezés
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-medium" href="#signup-tab" data-bs-toggle="tab" role="tab" aria-selected="false">
              <i class="ci-user me-2 mt-n1"></i>Regisztráció
            </a>
          </li>
        </ul>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Bezárás"></button>
      </div>
      
      <div class="modal-body tab-content py-4">
        <!-- Bejelentkezés Form -->
        <form class="needs-validation tab-pane fade show active" novalidate id="signin-tab" method="post" action="login-logic.php">
          <div class="mb-3">
            <label class="form-label" for="si-email">Email cím</label>
            <input class="form-control" type="email" id="si-email" name="email" placeholder="admin@gmail.com" required>
            <div class="invalid-feedback">Érvényes email címet adjon meg!</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="si-password">Jelszó</label>
            <div class="password-toggle">
              <input class="form-control" type="password" id="si-password" name="password" placeholder="1234" required>
              <label class="password-toggle-btn" aria-label="Jelszó megjelenítése/elrejtése">
                <input class="password-toggle-check" type="checkbox">
                <span class="password-toggle-indicator"></span>
              </label>
            </div>
          </div>
          <div class="mb-3 d-flex flex-wrap justify-content-between">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" id="si-remember" name="remember">
              <label class="form-check-label" for="si-remember">Emlékezz rám</label>
            </div>
            <a class="fs-sm" href="#">Elfelejtett jelszó?</a>
          </div>
          <button class="btn btn-primary btn-shadow d-block w-100" type="submit">Bejelentkezés</button>
        </form>
        
        <!-- Regisztráció Form -->
        <form class="needs-validation tab-pane fade" novalidate id="signup-tab" method="post" action="register-logic.php">
          <div class="mb-3">
            <label class="form-label" for="reg-fn">Keresztnév</label>
            <input class="form-control" name="first_name" type="text" required id="reg-fn">
            <div class="invalid-feedback">Kérjük, adja meg a keresztnevét!</div>

            <label class="form-label" for="reg-ln">Vezetéknév</label>
            <input class="form-control" name="last_name" type="text" required id="reg-ln">
            <div class="invalid-feedback">Kérjük, adja meg a vezetéknevét!</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="reg-email">E-mail cím</label>
            <input class="form-control" name="email" type="email" required id="reg-email">
            <div class="invalid-feedback">Kérjük, adjon meg egy érvényes e-mail címet!</div>

            <label class="form-label" for="reg-phone">Telefonszám</label>
            <input class="form-control" name="phone_number" type="text" required id="reg-phone">
            <div class="invalid-feedback">Kérjük, adja meg a telefonszámát!</div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="su-password">Jelszó</label>
            <div class="password-toggle">
              <input class="form-control" type="password" id="su-password" required>
              <label class="password-toggle-btn" aria-label="Jelszó megjelenítése/elrejtése">
                <input class="password-toggle-check" type="checkbox">
                <span class="password-toggle-indicator"></span>
              </label>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="su-password-confirm">Jelszó megerősítése</label>
            <div class="password-toggle">
              <input class="form-control" type="password" id="su-password-confirm" required>
              <label class="password-toggle-btn" aria-label="Jelszó megjelenítése/elrejtése">
                <input class="password-toggle-check" type="checkbox">
                <span class="password-toggle-indicator"></span>
              </label>
            </div>
          </div>
          <button class="btn btn-primary btn-shadow d-block w-100" type="submit">Regisztráció</button>
        </form>
      </div>
    </div>
  </div>
</div>


<main class="page-wrapper">

<!-- Navbar 3 Level (Light) -->
<header class="shadow-sm">
  <!-- Topbar -->
  <div class="topbar topbar-dark bg-linecolor">
    <div class="container">
      <div class="topbar-text dropdown d-md-none">
        <a class="topbar-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Hasznos linkek</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="tel:+36303559753"><i class="ci-support text-muted me-2"></i>+36 30 355 9753</a></li>
          <li><a class="dropdown-item" href="checkout-complete.php"><i class="ci-location text-muted me-2"></i>Rendelés nyomkövetése</a></li>
        </ul>
      </div>
      <div class="topbar-text text-nowrap d-none d-md-inline-block">
        <i class="ci-support"></i><span class="text-muted me-1">Ügyfélszolgálat</span><a class="topbar-link" href="tel:+36303559753">+36 30 355 9753</a>
      </div>
      <div class="tns-carousel tns-controls-static d-none d-md-block">
        <div class="tns-carousel-inner" data-carousel-options="{&quot;mode&quot;: &quot;gallery&quot;, &quot;nav&quot;: false}">
          <div class="topbar-text">Ingyenes szállítás 10 000 Ft felett</div>
          <div class="topbar-text">30 napon belül pénzvisszafizetés</div>
          <div class="topbar-text">Barátságos ügyfélszolgálat 24/7</div>
        </div>
      </div>
      <div class="ms-3 text-nowrap">
        <a class="topbar-link me-4 d-none d-md-inline-block" href="checkout-complete.php"><i class="ci-location"></i>Rendelés nyomkövetése</a>
        <div class="topbar-text dropdown disable-autohide">
          <a class="topbar-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <img class="me-2" src="img/hu.png" width="20" alt="Hungary">HUF / Ft
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li class="dropdown-item">
              <select class="form-select form-select-sm">
                <option value="huf">HUF - Ft</option>
              </select>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Navigációs sáv -->
  <div class="navbar-sticky bg-light">
    <div class="navbar navbar-expand-lg navbar-light">
      <div class="container">
        <a class="navbar-brand d-none d-sm-block flex-shrink-0" href="index.php">
          <h1 class="mb-0">PureLine</h1>
        </a>
        <div class="input-group d-none d-lg-flex mx-4">
          <form id="searchForm" method="POST" action="search-results.php" class="position-relative" style="flex: 1 1 auto;">
            <input class="form-control rounded-end pe-5" type="text" name="search" placeholder="Termék keresése" required>
            <i class="ci-search position-absolute top-50 end-0 translate-middle-y text-muted fs-base me-3" 
               style="cursor: pointer;" onclick="document.getElementById('searchForm').submit();"></i>
          </form>
        </div>
        <div class="navbar-toolbar d-flex flex-shrink-0 align-items-center">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-tool navbar-stuck-toggler" href="#">
            <span class="navbar-tool-tooltip">Menü kibővítése</span>
            <div class="navbar-tool-icon-box">
              <i class="navbar-tool-icon ci-menu"></i>
            </div>
          </a>
          <a class="navbar-tool d-none d-lg-flex" href="wishlist.php">
            <?php if ($wishlist_count > 0): ?>
              <span class="navbar-tool-label"><?=$wishlist_count ?></span>
            <?php endif; ?>
            <span class="navbar-tool-tooltip">Kívánságlista</span>
            <div class="navbar-tool-icon-box">
              <i class="navbar-tool-icon ci-heart"></i>
            </div>
          </a>
        </div>

        <?php if (is_logged_in()) { ?>
          <a class="navbar-tool ms-1 ms-lg-0 me-n1 me-lg-2" href="<?php echo $_SESSION['user']['user_type'] === 'admin' ? 'admin-account-orders.php' : 'account-orders.php'; ?>">
        <?php } else { ?>
          <a class="navbar-tool ms-1 ms-lg-0 me-n1 me-lg-2" href="#signin-modal" data-bs-toggle="modal">
        <?php } ?>
            <div class="navbar-tool-icon-box"><i class="navbar-tool-icon ci-user"></i></div>
            <div class="navbar-tool-text ms-n3">
              <?php if (is_logged_in()) { ?>
                <small>Szia, <?=$_SESSION['user']['last_name']?></small>
              <?php } else {  ?>
                <small>Szia, Bejelentkezés</small>
              <?php } ?>
              Fiókom
            </div>
          </a>
          <div class="navbar-tool dropdown ms-3">
            <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="cart.php">
              <span class="navbar-tool-label"><?=$cart_count ?></span>
              <i class="navbar-tool-icon ci-cart"></i>
            </a>
            <a class="navbar-tool-text" href="cart.php"><small>Kosaram</small><?=number_format($cart_total, 2) ?> Ft</a>
            <!-- Kosár legördülő menü -->
            <div class="dropdown-menu dropdown-menu-end">
              <div class="widget widget-cart px-3 pt-2 pb-3" style="width: 20rem;">
                <div style="height: 15rem;" data-simplebar data-simplebar-auto-hide="false">
                  <?php foreach ($cart_items as $key => $item) { ?>
                    <div class="widget-cart-item pb-2 border-bottom">
                      <a class="btn-close text-danger" href="cart-process-remove.php?id=<?= $item["pro"]["id"]?>" type="button" aria-label="Eltávolítás">
                        <span aria-hidden="true">&times;</span>
                      </a>
                      <div class="d-flex align-items-center">
                        <a class="flex-shrink-0" href="product.php?id=<?= $item["pro"]["id"]?>">
                          <img src="<?=get_product_thumb($item["pro"]["photos"])?>" width="64" alt="Termék">
                        </a>
                        <div class="ps-2">
                          <h6 class="widget-product-title"><a href="product.php?id=<?= $item["pro"]["id"]?>"><?=substr($item["pro"]["name"],0,20)?> ...</a></h6>
                          <div class="widget-product-meta">
                            <span class="text-accent me-2"><?=number_format($item["pro"]["price"], 2)?> Ft</span>
                            <span class="text-muted">x <?=$item["quantity"]?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
                  <div class="fs-sm me-2 py-2">
                    <span class="text-muted">Összesen:</span>
                    <span class="text-accent fs-base ms-1"><?=number_format($cart_total) ?> Ft</span>
                  </div>
                  <a class="btn btn-outline-secondary btn-sm" href="cart.php">Kosárhoz<i class="ci-arrow-right ms-1 me-n1"></i></a>
                  <?php if (!empty($cart_items)) { ?>
                    <!-- Ha a kosár nem üres, folytassa a fizetéssel -->
                    <a class="btn btn-primary btn-sm d-block w-100" href="checkout.php">
                      <i class="ci-card me-2 fs-base align-middle"></i>Fizetés
                    </a>
                  <?php } else { ?>
                    <!-- Ha a kosár üres, irányítson át a kosár oldalára -->
                    <a class="btn btn-primary btn-sm d-block w-100" href="cart.php">
                      <i class="ci-card me-2 fs-base align-middle"></i>Fizetés
                    </a>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-expand-lg navbar-light navbar-stuck-menu mt-n2 pt-0 pb-2">
        <div class="container">
          <div class="collapse navbar-collapse" id="navbarCollapse">
            <!-- Keresés -->
            <div class="input-group d-lg-none my-3">
              <form id="searchForm" method="POST" action="search-results.php" class="position-relative" style="flex: 1 1 auto;">
                <input class="form-control rounded-end pe-5" type="text" name="search" placeholder="Termék keresése" required>
                <i class="ci-search position-absolute top-50 end-0 translate-middle-y text-muted fs-base me-3" 
                   style="cursor: pointer;" onclick="document.getElementById('searchForm').submit();"></i>
              </form>
            </div>

            <!-- Departments menu -->
            <ul class="navbar-nav navbar-mega-nav pe-lg-2 me-lg-2">
              <li class="nav-item dropdown">
                <a class="nav-link ps-lg-0" href="index.php"><i class="ci-home me-2"></i>Főoldal</a>
              </li>
            </ul>

            <!-- Primary menu -->
            <ul class="navbar-nav">
              <li class="nav-item dropdown"><a class="nav-link" href="shop.php">Bolt</a></li>
              <li class="nav-item dropdown"><a class="nav-link" href="shop.php?&gender=man">Férfiak</a></li>
              <li class="nav-item dropdown"><a class="nav-link" href="shop.php?gender=woman">Nők</a></li>
              <li class="nav-item dropdown"><a class="nav-link" href="shop.php?gender=kid">Gyerekek</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<?php if (isset($_SESSION['alert'])): ?>
  <div class="container pt-5">
    <div id="alert-box" class="alert alert-<?= htmlspecialchars($_SESSION['alert']['type']) ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['alert']['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
  <script>
    // Az üzenet 5000 ms (5 másodperc) múlva eltűnik
    setTimeout(() => {
      const alertBox = document.getElementById('alert-box');
      if (alertBox) {
        alertBox.classList.remove('show'); // Bootstrap fade hatás
        alertBox.classList.add('fade');   // Eltűnés effekt
        setTimeout(() => alertBox.remove(), 150); // Biztos eltávolítás
      }
    }, 5000);
  </script>
  <?php unset($_SESSION['alert']); ?>
<?php endif; ?>


