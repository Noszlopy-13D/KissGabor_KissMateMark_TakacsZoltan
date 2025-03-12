<?php
require_once('files/functions.php');
require_once('files/header.php');

// A bejelentkezett felhasználó adatainak lekérdezése
if (isset($_SESSION['user'])) {
    $u = $_SESSION['user'];
    $user_id = $u['id'];

    // Lekérdezzük a felhasználó legutóbbi rendelését
    $sql = "SELECT id, order_date, total_price FROM orders WHERE customer_id = $user_id ORDER BY order_date DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Ha van rendelés, az utolsó megrendelés megjelenítése
      $order = $result->fetch_assoc();
      $formatted_date = date('Y-m-d H:i:s', $order["order_date"]);

  
      echo '
      <div class="container pb-5 mb-sm-4">
          <div class="pt-5">
              <div class="card py-3 mt-sm-3">
                  <div class="card-body text-center">
                      <h2 class="h4 pb-3">Köszönjük a rendelésedet!</h2>
                      <p class="fs-sm mb-2">Értékeljük a vásárlásodat, a rendelésed sikeresen rögzítve lett.</p>
                      <p class="fs-sm mb-2">A legutóbbi rendelésed száma: <span class="fw-medium">' . $order["id"] . '</span></p>
                      <p class="fs-sm mb-2">Rendelés dátuma: ' . $formatted_date . '</p>
                      <p class="fs-sm mb-2">Teljes ár: ' . number_format($order["total_price"], 2) . ' Ft</p>
                      <p class="fs-sm">További részleteket nézhetsz meg vagy folytathatod a vásárlást az alábbiakban:</p>
                      <a class="btn btn-secondary mt-3 me-3" href="shop.php">Vissza a vásárláshoz</a>
                      <a class="btn btn-primary mt-3" href="' . ( $_SESSION['user']['user_type'] === 'admin' ? 'admin-account-orders.php' : 'account-orders.php') . '"><i class="ci-location"></i>&nbsp;Menj a rendeléseimhez</a>
                  </div>
              </div>
          </div>
      </div>';
  } else {  
        // Ha nincs rendelés, egy üzenet megjelenítése
        echo '
        <div class="container pb-5 mb-sm-4">
            <div class="pt-5">
              <div class="card py-3 mt-sm-3">
                <div class="card-body text-center">
                  <h2 class="h4 pb-3">Még nincs rendelésed.</h2>
                  <p class="fs-sm mb-2">Úgy tűnik, még nem adtál le rendelést.</p>
                  <a class="btn btn-secondary mt-3" href="shop.php">Vissza a vásárláshoz</a>
                </div>
              </div>
            </div>
        </div>';
    }
} else {
    echo '
    <div class="container pb-5 mb-sm-4">
    <div class="pt-5">
      <div class="card py-3 mt-sm-3">
        <div class="card-body text-center">
          <h2 class="h4 pb-3">A felhasználó nincs bejelentkezve.</h2>
        </div>
      </div>
    </div>
</div>';
}

require_once('files/footer.php');
?>
