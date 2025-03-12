<?php
require_once('files/functions.php');
protected_area();

if (isset($_POST['id'])) {
    $category_id = intval($_POST['id']);

    // Lekérdezzük a kategóriát, hogy megtudjuk a kapcsolódó képet
    $sql = "SELECT photo FROM categories WHERE id = $category_id LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $category = $result->fetch_assoc();
        $photoData = json_decode($category['photo'], true);
        
        // Ellenőrizzük, hogy van-e kép, és töröljük az elsőt
        if (isset($photoData[0]['src'])) {
            $mainPhotoPath = $photoData[0]['src'];
            if (file_exists($mainPhotoPath)) {
                unlink($mainPhotoPath); // Töröljük a fő képet
            }

            // Töröljük a thumb_ kezdetű képet
            $thumbPhotoPath = dirname($mainPhotoPath) . '/thumb_' . basename($mainPhotoPath);
            if (file_exists($thumbPhotoPath)) {
                unlink($thumbPhotoPath); // Töröljük a mini képet
            }
        }

        // Törlés a categories táblából
        $deleteSql = "DELETE FROM categories WHERE id = $category_id";
        if ($conn->query($deleteSql) === TRUE) {
            // Sikeres törlés
            echo "A kategória és a kapcsolódó képek sikeresen törölve.";
        } else {
            // Hiba kezelése
            http_response_code(500); // Belső szerverhiba
            echo "Hiba a kategória törlésekor: " . $conn->error;
        }
    } else {
        http_response_code(404); // Nem található
        echo "A kategória nem található.";
    }
} else {
    http_response_code(400); // Hibás kérés
    echo "Érvénytelen kategóriaazonosító.";
}
?>
