<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['doctorID'])) {
    require 'db-config.php';
    global $pdo;
    $doctorID = $_SESSION['doctorID'];
    $patientID = trim($_POST['patientID']);
    $procedureDate = trim($_POST['procedureDate']);
    $procedureDetails = trim($_POST['procedureDetails']);
    $notes = trim($_POST['notes']);
    $procedureID = trim($_POST['procedure_id']);

    try {
        // Check the number of previous visits
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM PatientRecords WHERE patientID = ? AND procedureDate < ?");
        $stmt->execute([$patientID, $procedureDate]);
        $visit_count = $stmt->fetchColumn();

        // Get the price of the procedure
        $stmt = $pdo->prepare("SELECT price FROM Procedures WHERE procedureID = ?");
        $stmt->execute([$procedureID]);
        $original_price = $stmt->fetchColumn();

        // Insert the new patient record
        $stmt = $pdo->prepare("INSERT INTO PatientRecords (patientID, doctorID, procedureDate, procedureDetails, notes, procedureID, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$patientID, $doctorID, $procedureDate, $procedureDetails, $notes, $procedureID, $original_price]);

        header("Location: ../add_patient_records.php?message=" . urlencode("Patient record was added successfully.") . "&type=success");
        exit();
    } catch (PDOException $e) {
        header("Location: ../add_patient_records.php?message=" . urlencode("Error occurred: " . $e->getMessage()) . "&type=alert");
        exit();
    }
} else {
    header("Location: ../index.php?message=Nem jogosult a művelet végrehajtására.");
    exit();
}
?>
