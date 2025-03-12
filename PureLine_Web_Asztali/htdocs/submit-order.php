<?php
require_once('files/functions.php');

$user = $_SESSION["user"];

$total = 0;
foreach ($_SESSION["cart"] as $key => $val) {
    $total += $val["quantity"] * $val["pro"]["price"];
}

db_insert(
    "orders",
    [
        "customer_id" => $user["id"],
        "order_status" => 1,
        "shipping" => json_encode($_SESSION["shipping"], JSON_UNESCAPED_UNICODE),
        "cart" => json_encode($_SESSION["cart"], JSON_UNESCAPED_UNICODE),
        "user" => json_encode($_SESSION["user"], JSON_UNESCAPED_UNICODE),
        "order_date" => time(),
        "total_price" => $total,
    ]
);

$_SESSION["cart"] = null;
unset($_SESSION["cart"]);
unset($_SESSION["shipping"]);

header("Location: checkout-complete.php");