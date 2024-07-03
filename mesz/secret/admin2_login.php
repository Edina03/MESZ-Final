<?php
    session_start();

    // Felhasználói adatok
    $users = array(
        'mesz' => '1234'
    );

    // Ellenőrzés
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (isset($users[$username]) && $users[$username] === $password) {
            // Sikeres bejelentkezés
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("Location: ../admin2.php"); // Átirányítás az adminisztrációs oldalra
            exit();
        } else {
            // Sikertelen bejelentkezés
            $error = "Incorect username or password.";
        }
    }
    ?>