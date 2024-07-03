<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db-config.php';
    global $pdo;

    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    $sql = "SELECT * FROM Doctor WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['doctorID'] = $row['doctorID'];
            $_SESSION['firstName'] = $row['firstName'];
            $_SESSION['lastName'] = $row['lastName'];
            $_SESSION['phoneNumber'] = $row['phoneNumber'];
            $_SESSION['email'] = $row['email'];

            if ($remember_me) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_me_cookie', $token, time() + (86400 * 30), "/"); // 30 days cookie
                $sql = "UPDATE Doctor SET remember = :token WHERE doctorID = :doctorID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->bindParam(':doctorID', $row['doctorID'], PDO::PARAM_INT);
                $stmt->execute();
            }

            header("Location: ../index.php");
            exit();
        } else {
                header("Location: ../login.php?message=" . urlencode("Invalid email or password.") . "&type=alert");
        }
    } else {
        header("Location: ../login.php?message=" . urlencode("Invalid email or password.") . "&type=alert");
    }
}
?>
