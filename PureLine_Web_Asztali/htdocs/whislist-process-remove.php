<?php
require_once("files/functions.php");

$id = (int)($_GET["id"]);

// Ellenőrizzük, hogy a wishlist létezik-e, és ha igen, eltávolítjuk a terméket
if (isset($_SESSION["wishlist"])) {
    foreach ($_SESSION["wishlist"] as $key => $v) {
        if ($v["pro"]["id"] == $id) {
            unset($_SESSION["wishlist"][$key]);
            break; // Kilépünk a ciklusból, ha megtaláltuk és eltávolítottuk
        }
    }
}

// Értesítés a felhasználónak
alert("success", "Termék sikeresen eltávolítva a kívánságlistából.");
header("Location: wishlist.php");
exit; // Fontos, hogy az exit-tel megakadályozzuk a további végrehajtást
?>
