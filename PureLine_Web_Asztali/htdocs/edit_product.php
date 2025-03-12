<?php
require_once('files/functions.php');
protected_area();

if (isset($_POST['id'])) {
    $product_id = intval($_POST['id']);
    $name = $_POST['name'];
    $price = $_POST['price'];
    $photo_path = null;

    // Lekérdezzük a jelenlegi kép útvonalát az adatbázisból
    $currentPhotoQuery = "SELECT photos FROM products WHERE id = $product_id";
    $currentPhotoResult = $conn->query($currentPhotoQuery);
    $currentPhoto = null;

    if ($currentPhotoResult->num_rows > 0) {
        $currentPhotoRow = $currentPhotoResult->fetch_assoc();
        $currentPhoto = json_decode($currentPhotoRow['photos'], true);
        $currentPhotoPath = isset($currentPhoto[0]['src']) ? $currentPhoto[0]['src'] : null;
    }

    // Fájl feltöltés kezelése, ha van
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Feltöltési könyvtár
        $upload_dir = 'uploads/'; // A könyvtár, ahová a képet fel szeretnénk tölteni
        $filename = basename($_FILES['photo']['name']); // A feltöltött fájl neve
        $photo_path_full = $upload_dir . $filename; // A fájl teljes útvonala

        // A feltöltött fájl áthelyezése
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path_full)) {
            // Az útvonalak tárolása JSON formátumban
            $photo_path = '[{"thumb":"' . $photo_path_full . '","src":"' . $photo_path_full . '"}]';

            // A régi kép törlése, ha létezik
            if ($currentPhotoPath && file_exists($currentPhotoPath)) {
                unlink($currentPhotoPath); // A régi fájl törlése
            }
        } else {
            http_response_code(500); // Belső szerverhiba
            echo "Hiba a fájl feltöltésekor.";
            exit;
        }
    }

    // A products tábla frissítése
    $updateSql = "UPDATE products SET name = '$name', price = '$price'";
    if ($photo_path) {
        $updateSql .= ", photos = '$photo_path'";
    }
    $updateSql .= " WHERE id = $product_id";

    if ($conn->query($updateSql) === TRUE) {
        echo "A termék sikeresen frissítve.";
    } else {
        http_response_code(500); // Belső szerverhiba
        echo "Hiba a termék frissítésekor: " . $conn->error;
    }
} else {
    http_response_code(400); // Hibás kérés
    echo "Érvénytelen termékadatok.";
}
?>
