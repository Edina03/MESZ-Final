<?php
require 'db-config.php';
global $pdo;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = trim($_POST['token']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        header("Location: ../reset_password.php?token=" . urlencode($token) . "&message=" . urlencode("The passwords do not match.") . "&type=alert");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("SELECT patientID FROM Patient WHERE forgot = ?");
        $stmt->execute([$token]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt2 = $pdo->prepare("SELECT doctorID FROM Doctor WHERE forget = ?");
        $stmt2->execute([$token]);
        $doctor = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($patient) {
            $stmt = $pdo->prepare("UPDATE Patient SET password = ?, forgot = NULL WHERE forgot = ?");
            $stmt->execute([$hashed_password, $token]);
        } elseif ($doctor) {
            $stmt2 = $pdo->prepare("UPDATE Doctor SET password = ?, forget = NULL WHERE forget = ?");
            $stmt2->execute([$hashed_password, $token]);
        } else {
            header("Location: ../reset_password.php?token=" . urlencode($token) . "&message=" . urlencode("Invalid or expired token.") . "&type=alert");
            exit();
        }

        header("Location: ../login.php?message=" . urlencode("The password was succesful.") . "&type=success");
        exit();
    } catch (PDOException $e) {
        header("Location: ../reset_password.php?token=" . urlencode($token) . "&message=" . urlencode("An error occurred during password reset: " . $e->getMessage()) . "&type=alert");
        exit();
    }
} else {
    header("Location: ../index.php?message=" . urlencode("Invalid request.") . "&type=alert");
    exit();
}