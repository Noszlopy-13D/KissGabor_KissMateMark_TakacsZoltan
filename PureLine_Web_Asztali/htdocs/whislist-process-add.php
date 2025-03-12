<?php
require_once("files/functions.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"] ?? null;

    if ($id === null) {
        die("Érvénytelen termék azonosító.");
    }

    $pro = get_product($id);
    if ($pro === null) {
        die("Termék nem található.");
    }

    // Hozzáadjuk a terméket a wishlisthez
    $_SESSION["wishlist"][$id] = $pro; // Külön tároló a wishlisthez
    alert("success", "Termék sikeresen hozzáadva a kívánságlistához.");

    $redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $redirect_url");
    exit(); 
}
