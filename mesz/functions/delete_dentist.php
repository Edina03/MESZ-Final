<?php
include 'db-config.php';

global $pdo;

// Retrieve doctorID from POST request and sanitize it
$doctorID = isset($_POST['doctorID']) ? intval($_POST['doctorID']) : null;

if (!empty($doctorID)) {
    try {
        // Prepare the SQL statement to prevent SQL injection
        $sql = $pdo->prepare("DELETE FROM Doctor WHERE doctorID = :doctorID");
        $sql->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);

        if ($sql->execute()) {             
            header("Location: ../admin2.php");
            exit(); 
        } else {
            echo "Error: Unable to delete the record.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: doctorID is not provided or invalid.";
}

// Close the PDO connection
$pdo = null;
?>
