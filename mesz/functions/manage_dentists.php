<?php
include 'db-config.php';

global $pdo;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $operation = $_POST["operation"];
    $doctorID = isset($_POST["doctorID"]) ? $_POST["doctorID"] : null;
    $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : null;
    $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : null;
    $password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_BCRYPT) : null;
    $phoneNumber = isset($_POST["phoneNumber"]) ? $_POST["phoneNumber"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;
    $worktime = isset($_POST["worktime"]) ? $_POST["worktime"] : null;
    $specialisation = isset($_POST["specialisation"]) ? $_POST["specialisation"] : null;
    $forget = isset($_POST["forget"]) ? $_POST["forget"] : null;
    $remember = isset($_POST["remember"]) ? $_POST["remember"] : null;

    if ($operation == "insert" || $operation == "update") {
        // Check if email already exists
        $checkEmailSql = "SELECT doctorID FROM Doctor WHERE email = :email";
        $stmt = $pdo->prepare($checkEmailSql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $existingDoctor = $stmt->fetch(PDO::FETCH_ASSOC);

        $existingDoctorID = $existingDoctor ? $existingDoctor['doctorID'] : null;
    }

    if ($operation == "insert") {
        if ($existingDoctorID === null) {
            $sql = "INSERT INTO Doctor (firstName, lastName, password, phoneNumber, email, worktime, specialisation, forget, remember)
                    VALUES (:firstName, :lastName, :password, :phoneNumber, :email, :worktime, :specialisation, :forget, :remember)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':worktime', $worktime, PDO::PARAM_STR);
            $stmt->bindParam(':specialisation', $specialisation, PDO::PARAM_STR);
            $stmt->bindParam(':forget', $forget, PDO::PARAM_STR);
            $stmt->bindParam(':remember', $remember, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Input was successful";
            } else {
                echo "Error: " . $stmt->errorInfo()[2];
            }
        } else {
            echo "Email already exists";
        }
    } elseif ($operation == "update") {
        if (!empty($doctorID)) {
            if ($existingDoctorID === null || $existingDoctorID == $doctorID) {
                $sql = "UPDATE Doctor
                        SET firstName = :firstName, lastName = :lastName, password = :password, phoneNumber = :phoneNumber, email = :email, worktime = :worktime, specialisation = :specialisation
                        WHERE doctorID = :doctorID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
                $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':worktime', $worktime, PDO::PARAM_STR);
                $stmt->bindParam(':specialisation', $specialisation, PDO::PARAM_STR);
                $stmt->bindParam(':doctorID', $doctorID, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo "Update was succesful.<br>";
                } else {
                    echo "Error: " . $stmt->errorInfo()[2] . "<br>";
                }
            } else {
                echo "Email already exists<br>";
            }
        } else {
            echo "Doctor ID is required for the modification<br>";
        }
    }
}

header("Location: ../admin2.php");
exit();
?>
