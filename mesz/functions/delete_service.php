<?php
session_start();
global $pdo;
include 'db-config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize procedureID
    $procedureID = isset($_POST['procedureID']) ? intval($_POST['procedureID']) : null;

    if (!empty($procedureID)) {
        try {
            $sql = $pdo->prepare("DELETE FROM Procedures WHERE procedureID = :procedureID");
            $sql->bindParam(':procedureID', $procedureID, PDO::PARAM_INT);

            if ($sql->execute()) {
                // Sikeres törlés után átirányítás
                header("Location: ../admin2.php");
                exit(); // Biztonsági okokból fontos az exit() hívása azonnal az átirányítás után
            } else {
                echo "Error";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error";
    }
} else {
    // No form submitted
    echo "Incorrectly completed";
}

// Close the PDO connection
$pdo = null;
?>
