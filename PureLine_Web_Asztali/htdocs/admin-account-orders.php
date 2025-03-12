<?php
require_once('files/functions.php');
protected_area();
require_once('files/header.php');
$u = $_SESSION['user'];

// Lekérdezzük a bejelentkezett felhasználó adatait, beleértve a user_type mezőt
$user_id  = $u["id"];
$sql_user = "SELECT id, user_type FROM users WHERE id = $user_id";
$result_user = $conn->query($sql_user);
if ($result_user->num_rows > 0) {
    $user_row = $result_user->fetch_assoc();
    $current_user_id = $user_row['id'];
    $user_type = $user_row['user_type'];
} else {
    // Ha a felhasználó nem található, kezeljük az esetet
    echo "Felhasználó nem található.";
    logout();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);

    // Ellenőrizzük a kérést, hogy állapotfrissítés vagy törlés
    if (isset($_POST['order_status'])) {
        $order_status = intval($_POST['order_status']);
        // SQL lekérdezés a rendelés státuszának frissítésére
        $sql = "UPDATE orders SET order_status = $order_status WHERE id = $order_id";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'A rendelés státusza sikeresen frissítve.'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Hiba a rendelés státuszának frissítésekor: ' . $conn->error];
        }
                // Frissítés az alert eltüntetéséhez
                echo '<script>
                setTimeout(function() {
                    document.querySelector(".alert").style.display = "none";
                }, 4000);
              </script>';
    } elseif (isset($_POST['delete_order'])) {
        // SQL lekérdezés a rendelés törlésére
        $sql = "DELETE FROM orders WHERE id = $order_id";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'A rendelés sikeresen törölve.'];
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Hiba a rendelés törlésekor: ' . $conn->error];
        }
        
        // Frissítés az alert eltüntetéséhez
        echo '<script>
                setTimeout(function() {
                    document.querySelector(".alert").style.display = "none";
                }, 4000);
              </script>';
    }
}
 if (isset($_SESSION['alert'])): ?>
    <div class=" alert alert-<?= $_SESSION['alert']['type'] ?> alerismissible fade show text-center" role="alert">
        <?= $_SESSION['alert']['message'] ?>
    </div>
    <?php unset($_SESSION['alert']); // Töröld az alertet a sessionből ?>
<?php endif; ?>


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
            <h1 class="h3 text-light mb-0">Rendeléseim</h1>
        </div>
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <?php require_once('files/admin-account-sidebar.php'); ?>

        <!-- Tartalom -->
        <section class="col-lg-8">
            <!-- Eszköztár -->
            <div class="d-flex justify-content-between align-items-center pt-lg-2 pb-4 pb-lg-5 mb-lg-3">
                <div class="d-flex align-items-center">
                    <label class="d-none d-lg-block fs-sm text-light text-nowrap opacity-75 me-2" for="order-status">Rendelések szűrése:</label>
                    <label class="d-lg-none fs-sm text-nowrap opacity-75 me-2" for="order-status">Rendelések szűrése:</label>

                    <?php
                    // A kiválasztott státusz lekérdezése
                    $selected_status = isset($_GET['order_status']) ? intval($_GET['order_status']) : null;
                    ?>

                    <form method="GET" action="">
                        <select class="form-select" id="order-status" name="order_status" onchange="this.form.submit()">
                            <option value="-1" <?php echo ($selected_status === -1) ? 'selected' : ''; ?>>Összes</option>
                            <option value="0" <?php echo ($selected_status === 0) ? 'selected' : ''; ?>>Kiszállítva</option>
                            <option value="1" <?php echo ($selected_status === 1) ? 'selected' : ''; ?>>Folyamatban</option>
                            <option value="2" <?php echo ($selected_status === 2) ? 'selected' : ''; ?>>Késik</option>
                            <option value="3" <?php echo ($selected_status === 3) ? 'selected' : ''; ?>>Törölve</option>
                        </select>
                    </form>
                </div>
                <a class="btn btn-primary btn-sm d-none d-lg-inline-block" href="logout.php"><i class="ci-sign-out me-2"></i>Kijelentkezés</a>
            </div>

            <?php
            // SQL lekérdezés a rendelésekre
            $sql = "SELECT id, order_date, order_status, total_price FROM orders";

            // Ha a felhasználó nem admin, csak a saját rendeléseit látja
            if ($user_type != 'admin') {
                $sql .= " WHERE customer_id = $current_user_id";
            }

            // Csak akkor adjuk hozzá a státusz szűrést, ha nem az "Összes" opciót választották
            if ($selected_status !== null && $selected_status !== -1) {
                // Ha van WHERE feltétel, akkor 'AND'-del kell folytatni, ha nincs, akkor 'WHERE'-rel
                if ($user_type == 'admin') {
                    $sql .= " WHERE order_status = $selected_status";
                } else {
                    $sql .= " AND order_status = $selected_status";
                }
            }


            // Lekérdezés végrehajtása
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                echo '<div class="table-responsive fs-md mb-4">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Rendelési szám</th>
                                    <th>Rendelés dátuma</th>
                                    <th>Állapot</th>
                                    <th>Összesen</th>
                                    <th class="text-center">Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>';
            
                // Státuszok szövegei és színei
                $status_labels = [
                    0 => ['text' => 'Kiszállítva', 'class' => 'bg-success'], // Zöld
                    1 => ['text' => 'Folyamatban', 'class' => 'bg-warning'], // Sárga
                    2 => ['text' => 'Késik', 'class' => 'bg-secondary'], // Szürke
                    3 => ['text' => 'Törölve', 'class' => 'bg-danger'], // Piros
                ];
            
                while($row = $result->fetch_assoc()) {
                    // Átalakítjuk az Unix timestamp-et olvasható dátumformátumra
                    $formatted_date = date('Y-m-d H:i:s', $row["order_date"]);
            
                    // Lekérdezzük a megfelelő státusz szöveget és osztályt
                    $status_info = isset($status_labels[$row["order_status"]]) ? $status_labels[$row["order_status"]] : ['text' => 'Ismeretlen', 'class' => 'bg-light'];
            
                    echo '<tr>
                            <td class="py-3 text-center" data-label="Rendelési szám" ><a class="nav-link-style fw-medium fs-sm" href="#order-details" data-bs-toggle="modal">' . $row["id"] . '</a></td>
                            <td class="py-3" data-label="Rendelés dátuma">' . $formatted_date . '</td>
                            <td class="py-3" data-label="Állapot"><span class="badge ' . $status_info['class'] . ' m-0">' . $status_info['text'] . '</span></td>
                            <td class="py-3" data-label="Összesen">' . number_format($row["total_price"], 2) . ' Ft</td>
                            <td class="py-3" data-label="Műveletek">
                    <form method="POST" action="admin-account-orders.php" class="d-inline">
                        <input type="hidden" name="order_id" value="' . $row["id"] . '">
                        <select name="order_status" class="form-select form-select-sm w-auto d-inline" style="min-width: 120px;" onchange="this.form.submit()"> <!-- Szűkített legördülő menü -->
                            <option value="0"' . ($row["order_status"] == 0 ? ' selected' : '') . '>Kiszállítva</option>
                            <option value="1"' . ($row["order_status"] == 1 ? ' selected' : '') . '>Folyamatban</option>
                            <option value="2"' . ($row["order_status"] == 2 ? ' selected' : '') . '>Késik</option>
                            <option value="3"' . ($row["order_status"] == 3 ? ' selected' : '') . '>Törölve</option>
                        </select>
                    </form>
                    <form method="POST" action="admin-account-orders.php" class="d-inline ms-2"> <!-- Margin a legördülő menü és a Törlés gomb között -->
                        <input type="hidden" name="order_id" value="' . $row["id"] . '">
                        <button type="submit" name="delete_order" class="btn btn-danger btn-sm" onclick="return confirm(\'Biztosan törölni szeretné ezt a rendelést?\')">Törlés</button>
                    </form>
                            </td>
                          </tr>';
                }
            
                echo '</tbody>
                    </table>
                    </div>';
            } else {
                echo '
                <div class="text-center py-5">
                    <img src="img/no-orders.jpg" alt="Nincsenek elemek" class="mb-4" style="max-width: 100px;">
                    <h3>Nincsenek elemek</h3>
                    <p class="text-muted mb-4">Úgy tűnik, még nem adott le rendelést. Fedezze fel termékeinket és kezdje el a vásárlást!</p>
                    <a href="shop.php" class="btn btn-primary btn-lg">Vásárlás kezdése</a>
                </div>';
            }
            ?>

<?php require_once('files/footer.php'); ?>
