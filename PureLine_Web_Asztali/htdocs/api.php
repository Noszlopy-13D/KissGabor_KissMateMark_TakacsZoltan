<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$database = "shop";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

//RENDELÉSEK LEKÉRÉSE
if ($_SERVER['REQUEST_METHOD'] == 'GET' && strpos($_SERVER['REQUEST_URI'], '/orders') !== false) {
    $sql = "SELECT id, order_date, order_status, total_price FROM orders";
    $result = $conn->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode(["success" => true, "data" => $orders], JSON_PRETTY_PRINT);
} 

// POST KÉRÉSEK KEZELÉSE (TÖRLÉS / STÁTUSZ MÓDOSÍTÁS)
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && strpos($_SERVER['REQUEST_URI'], '/orders') !== false) {
    //JSON beolvasása
    $input = json_decode(file_get_contents("php://input"), true);
    $order_id = intval($input['order_id'] ?? 0);

    if (!$order_id) {
        echo json_encode(["success" => false, "message" => "Nincs megadva rendelés azonosító"]);
        exit;
    }

    if (isset($input['order_status'])) {
        // RENDELÉS STÁTUSZ MÓDOSÍTÁSA
        $order_status = intval($input['order_status']);
        $sql = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
        $sql->bind_param("ii", $order_status, $order_id);
    } elseif (isset($input['delete_order'])) {
        // RENDELÉS TÖRLÉSE
        $sql = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $sql->bind_param("i", $order_id);
    } else {
        echo json_encode(["success" => false, "message" => "Hibás kérés"]);
        exit;
    }

    if ($sql->execute()) {
        echo json_encode(["success" => true, "message" => "Sikeres művelet"]);
    } else {
        echo json_encode(["success" => false, "message" => "Hiba: " . $conn->error]);
    }
}
// Bejelentkezés ellenőrzése
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/api.php/login') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    $sql = $conn->prepare("SELECT id, first_name, email, user_type, password FROM users WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo json_encode(["success" => true, "user" => $user]);
        } else {
            echo json_encode(["success" => false, "message" => "Hibás jelszó"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Felhasználó nem található"]);
    }
}

$conn->close();
?>
