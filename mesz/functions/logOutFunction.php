<?php
session_start();
require 'db-config.php';
global $pdo;

// Delete cookie
if (isset($_SESSION['patientID'])) {
    if (isset($_COOKIE['remember_me_cookie'])) {
        unset($_COOKIE['remember_me_cookie']);
        setcookie('remember_me_cookie', '', ['expires' => time() - 3600, 'path' => '/']);
        $sql = "UPDATE Patient SET remember = NULL WHERE patientID = :patientID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':patientID', $_SESSION['patientID'], PDO::PARAM_INT);
        $stmt->execute();
    }
}

if (isset($_SESSION['doctorID'])) {
    if (isset($_COOKIE['remember_me_cookie'])) {
        unset($_COOKIE['remember_me_cookie']);
        setcookie('remember_me_cookie', '', ['expires' => time() - 3600, 'path' => '/']);
        $sql = "UPDATE Doctor SET remember = NULL WHERE doctorID = :doctorID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':doctorID', $_SESSION['doctorID'], PDO::PARAM_INT);
        $stmt->execute();
    }
}

// Destroy session
$_SESSION = array();
session_destroy();

header("Location: ../index.php");
exit();
?>
