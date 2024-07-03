<?php
require 'functions/db-config.php';
global $pdo;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare the select statement
    $stmt = $pdo->prepare("SELECT patientID FROM Patient WHERE auth = ?");
    if (!$stmt) {
        die("Prepare failed: " . $pdo->errorInfo());
    }

    // Execute the select statement
    $stmt->execute([$token]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $patientID = $result['patientID'];

        // Prepare the update statement
        $stmt = $pdo->prepare("UPDATE Patient SET auth = NULL WHERE patientID = ?");
        if (!$stmt) {
            die("Prepare failed: " . $pdo->errorInfo());
        }

        // Execute the update statement
        if ($stmt->execute([$patientID])) {
            header("Location: index.php?message=" . urlencode("Email confirmed") . "&type=success");
            exit();
        } else {
            header("Location: index.php?message=" . urlencode("Error occured: " . implode(", ", $stmt->errorInfo())) . "&type=alert");
            exit();
        }
    } else {
        header("Location: index.php?message=" . urlencode("Invalied token.") . "&type=alert");
        exit();
    }
} else {
    header("Location: index.php?message=" . urlencode("Missing token.") . "&type=alert");
    exit();
}
