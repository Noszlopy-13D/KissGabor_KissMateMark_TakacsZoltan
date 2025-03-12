<?php
require_once('files/functions.php'); // Az autentikálásért felelős függvények betöltése

class LoginTests {

    public function runTests() {
        echo "Tesztelés indítása...<br>"; // Tesztelés indítása

        // TC1.1: Helytelen email-cím és jelszó
        $this->testInvalidEmailAndPassword();

        // TC1.2: Létező email-cím helytelen jelszóval
        $this->testExistingEmailWithWrongPassword();

        // TC1.3: Hiányzó email-cím vagy jelszó
        $this->testMissingEmailOrPassword();

        // TC1.4: Sikeres bejelentkezés helyes email-cím és jelszó esetén
        $this->testValidEmailAndPassword();
    }
    // TC1.1: Helytelen email-cím és jelszó
    private function testInvalidEmailAndPassword() {
        $_POST['email'] = 'invalid@example.com'; // Hibás e-mail cím
        $_POST['password'] = 'wrongpassword'; // Hibás jelszó
        if (!$this->loginUser('invalid@example.com', 'wrongpassword')) {
            echo "✔ TC1.1: Helytelen email-cím és jelszó esetén a bejelentkezés nem sikerült.<br>";
        } else {
            echo "✘ TC1.1: Helytelen email-cím és jelszó esetén a bejelentkezés sikerült.<br>";
        }
    }

    // TC1.2: Létező email-cím helytelen jelszóval
    private function testExistingEmailWithWrongPassword() {
        $_POST['email'] = 'user@example.com'; // Létező e-mail cím
        $_POST['password'] = 'wrongpassword'; // Hibás jelszó
        if (!$this->loginUser('user@example.com', 'wrongpassword')) {
            echo "✔ TC1.2: Létező email-cím helytelen jelszóval a bejelentkezés nem sikerült.<br>";
        } else {
            echo "✘ TC1.2: Létező email-cím helytelen jelszóval a bejelentkezés sikerült.<br>";
        }
    }

    // TC1.3: Hiányzó email-cím vagy jelszó
    private function testMissingEmailOrPassword() {
        $_POST['email'] = ''; // Hiányzó email
        $_POST['password'] = ''; // Hiányzó jelszó
        if (!$this->loginUser('', '')) {
            echo "✔ TC1.3: Hiányzó email-cím vagy jelszó esetén a bejelentkezés nem sikerült.<br>";
        } else {
            echo "✘ TC1.3: Hiányzó email-cím vagy jelszó esetén a bejelentkezés sikerült.<br>";
        }
    }

    // TC1.4: Sikeres bejelentkezés helyes email-cím és jelszó esetén
    private function testValidEmailAndPassword() {
        $_POST['email'] = 'admin@gmail.com'; // Helyes e-mail cím
        $_POST['password'] = '1234'; // Helyes jelszó
        if ($this->loginUser('admin@gmail.com', '1234')) {
            echo "✔ TC1.4: Sikeres bejelentkezés helyes email-cím és jelszó esetén.<br>";
        } else {
            echo "✘ TC1.4: Sikeres bejelentkezés helyes email-cím és jelszó esetén nem sikerült.<br>";
        }
    }

    // Segédfüggvény a bejelentkezés szimulálásához
    private function loginUser($email, $password) {
        return login_user($email, $password);
    }
    
}

// Tesztek futtatása
$loginTests = new LoginTests();
$loginTests->runTests();
?>
