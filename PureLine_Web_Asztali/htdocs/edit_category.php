<?php
require_once('files/functions.php');
protected_area();

if (isset($_POST['id'])) {
    $category_id = intval($_POST['id']);
    $name = $_POST['name'];
    $photo_path = null;

    // Lekérdezzük a jelenlegi kép útvonalát az adatbázisból
    $currentPhotoQuery = "SELECT photo FROM categories WHERE id = $category_id";
    $currentPhotoResult = $conn->query($currentPhotoQuery);
    $currentPhoto = null;

    if ($currentPhotoResult->num_rows > 0) {
        $currentPhotoRow = $currentPhotoResult->fetch_assoc();
        $currentPhoto = json_decode($currentPhotoRow['photo'], true);
        $currentPhotoPath = isset($currentPhoto[0]['src']) ? $currentPhoto[0]['src'] : null;
    }

    // Fájl feltöltés kezelése, ha van
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; // Feltöltési könyvtár
        $filename = basename($_FILES['photo']['name']);
        $photo_path_full = $upload_dir . $filename;

        // A feltöltött fájl áthelyezése
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path_full)) {
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

    // A categories tábla frissítése
    $updateSql = "UPDATE categories SET name = '$name'";
    if ($photo_path) {
        $updateSql .= ", photo = '$photo_path'";
    }
    $updateSql .= " WHERE id = $category_id";

    if ($conn->query($updateSql) === TRUE) {
        echo "A kategória sikeresen frissítve.";
    } else {
        http_response_code(500); // Belső szerverhiba
        echo "Hiba a kategória frissítésekor: " . $conn->error;
    }
} else {
    http_response_code(400); // Hibás kérés
    echo "Érvénytelen kategória adatok.";
}
?>
