<?php
require_once("files/functions.php");

$id = (int)($_GET["id"]);

if (isset($_SESSION["cart"])) {
    foreach ($_SESSION["cart"] as $key => $v) {
        if ($v["pro"]["id"] == $id) {
            unset($_SESSION["cart"][$key]);
        }
    }
}


alert("success", "Termék sikeresen eltávolítva a kosárból.");
$redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $redirect_url");
exit(); 
