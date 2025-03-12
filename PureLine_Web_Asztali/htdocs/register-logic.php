<?php
require_once('files/functions.php');

$email = trim($_POST['email']);
$password = trim($_POST['password']);
$password_1 = trim($_POST['password_1']);
$phone_number = trim($_POST['phone_number']);
$last_name = trim($_POST['last_name']);
$first_name = trim($_POST['first_name']);

// Ellenőrizzük, hogy a két jelszó megegyezik-e
if ($password != $password_1) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'A jelszó nem egyezik.'];
    header('Location: login.php');
    die();
}

// Ellenőrizzük, hogy már létezik-e a felhasználó ezzel az e-mail címmel
$sql = "SELECT * FROM users WHERE email = '{$email}'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Már létezik felhasználó azonos e-mail-címmel.'];
    header('Location: login.php');
    die();
}

// Jelszó hash-elése
$password_hashed = password_hash($password, PASSWORD_DEFAULT);
$created = time();

// Ellenőrizzük, hogy az e-mail cím tartalmazza-e az "admin" szót, és ennek megfelelően állítjuk be a user_type-ot
if (strpos($email, 'admin') !== false) {
    $user_type = 'admin';
} else {
    $user_type = 'customer';
}

// Felhasználó beszúrása az adatbázisba
$sql = "INSERT INTO users (
        first_name,
        last_name,
        phone_number,
        password,
        email,
        user_type,
        created
) VALUES (
        '{$first_name}',
        '{$last_name}',
        '{$phone_number}',
        '{$password_hashed}',
        '{$email}',
        '{$user_type}',
        '{$created}'
)";

// Ha a beszúrás sikeres, akkor bejelentkeztetjük a felhasználót
if ($conn->query($sql)) {
    // Bejelentkeztetjük a felhasználót
    login_user($email, $password);
    
    // Lekérdezzük a felhasználó adatait
    $sql = "SELECT user_type FROM users WHERE email = '{$email}'";
    $res = $conn->query($sql);
    $user = $res->fetch_assoc();

    // Ellenőrizzük, hogy admin-e a felhasználó
    if ($user['user_type'] == 'admin') {
        header("Location: admin-account-orders.php"); // Admin oldalra irányítás
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Sikeres fiók létrehozás'];
    } else {
        header("Location: account-orders.php"); // Normál oldalra irányítás
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Sikeres fiók létrehozás'];
    }
    
    die();
} else {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Sikertelen fiók létrehozás'];
    header('Location: login.php');
    die();
}
