<?php
include 'db-config.php';

global $pdo;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $operation = $_POST["operation"];
    $procedureID = isset($_POST["procedureID"]) ? intval($_POST["procedureID"]) : null;
    $procedureName = isset($_POST["procedureName"]) ? $_POST["procedureName"] : null;
    $price = isset($_POST["price"]) ? floatval($_POST["price"]) : null;

    if ($operation == "insert") {
        $sql = "INSERT INTO Procedures (procedureName, price) VALUES (:procedureName, :price)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':procedureName', $procedureName, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Input was successful.";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    } elseif ($operation == "update") {
        if (!empty($procedureID)) {
            $sql = "UPDATE Procedures SET procedureName = :procedureName, price = :price WHERE procedureID = :procedureID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':procedureName', $procedureName, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':procedureID', $procedureID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "Update was succesful.";
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
        } else {
            echo "Procedure ID needed for updated.";
        }
    }
} else {
    echo "No data sent.";
}

header("Location: ../admin2.php");
exit();
?>
