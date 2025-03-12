<?php
require_once('files/functions.php');

$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Felhasználó bejelentkezése
if (login_user($email, $password)) {
    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Sikeres belépés.'];

    // Lekérdezzük a felhasználó adatait
    $sql = "SELECT user_type FROM users WHERE email = '{$email}'";
    $res = $conn->query($sql);
    $user = $res->fetch_assoc();

    // Ellenőrizzük, hogy admin-e a felhasználó
    if ($user['user_type'] == 'admin') {
        header("Location: admin-account-orders.php"); // Admin oldalra irányítás
    } else {
        header("Location: account-orders.php"); // Normál oldalra irányítás
    }

    exit();
} else {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Hibás email cím vagy jelszó.'];
    header('Location: login.php');
    exit();
}
