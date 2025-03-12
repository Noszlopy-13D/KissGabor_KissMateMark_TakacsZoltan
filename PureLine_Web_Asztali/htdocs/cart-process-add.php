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

    $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 1;
    if ($quantity <= 0) {
        die("A mennyiség érvénytelen.");
    }

    $pro["quantity"] = $quantity;
    $_SESSION["cart"][$id] = $pro;
    alert("success", "Termék sikeresen hozzáadva a kosárhoz.");

    $redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $redirect_url");
    exit(); 
}
