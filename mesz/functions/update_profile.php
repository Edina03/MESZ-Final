<?php
session_start();
header('Content-Type: application/json');

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : null;
    $phoneNumber = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;

    if (empty($newPassword) && empty($phoneNumber) && empty($email)) {
        $response["message"] = "There is no data to be refreshed.";
        echo json_encode($response);
        exit;
    }

    if (!isset($_SESSION['patientID'])) {
        $response["message"] = "I can't find this id.";
        echo json_encode($response);
        exit;
    }

    $patientID = $_SESSION['patientID'];

    require 'db-config.php';
    global $pdo;

    $sql = "UPDATE Patient SET ";

    $updates = [];
    if (!empty($newPassword)) {
        //(?=.*[!@#$%^&*()_+{}\[\]:;"\'<>,.?\/])
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $newPassword)) {
            $response["message"] = "The password needs to be at least 8 character, needs to have a number and a big case letter. ";
            echo json_encode($response);
            exit;
        }
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updates[] = "password = :password";
    }

    if (!empty($phoneNumber)) {
        $updates[] = "phoneNumber = :phoneNumber";
    }

    if (!empty($email)) {
        $updates[] = "email = :email";
    }

    if (!empty($updates)) {
        $sql .= implode(", ", $updates);
        $sql .= " WHERE patientID = :patientID";

        try {
            $stmt = $pdo->prepare($sql);

            if (!empty($newPassword)) {
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            }
            if (!empty($phoneNumber)) {
                $stmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
            }
            if (!empty($email)) {
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            }
            $stmt->bindParam(':patientID', $patientID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $response["success"] = true;
                $response["message"] = "Profile successfully updated.";
            } else {
                $response["message"] = "Error occured while refreshing: " . implode(" ", $stmt->errorInfo());
            }
        } catch (PDOException $e) {
            $response["message"] = "Error occured while refreshing: " . $e->getMessage();
        }
    }
} else {
    $response["message"] = "Invalid request.";
}

echo json_encode($response);