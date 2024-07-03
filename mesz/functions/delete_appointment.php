<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_SESSION["patientID"]) || isset($_SESSION["doctorID"]))) {
    require 'db-config.php';
    global $pdo;

    $appID = intval($_POST['appID']);

    try {
        $stmt = $pdo->prepare("SELECT schedule FROM Appointment WHERE appID = ?");
        $stmt->execute([$appID]);
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($appointment) {
            $currentDateTime = new DateTime();
            $appointmentDateTime = new DateTime($appointment['schedule']);

            $interval = $currentDateTime->diff($appointmentDateTime);
            $hoursDiff = ($interval->days * 24) + $interval->h + ($interval->i / 60);

            if ($hoursDiff > 4) {
                $stmt = $pdo->prepare("DELETE FROM Appointment WHERE appID = ?");
                $stmt->execute([$appID]);

                header("Location: ../view_appointments.php?message=" . urlencode("Appointment successfully deleted.") . "&type=success");
            } else {
                header("Location: ../view_appointments.php?message=" . urlencode("The appointment is no longer in a deletable state") . "&type=alert");
            }
        } else {
            header("Location: ../view_appointments.php?message=" . urlencode("The appointment cannot be found.") . "&type=alert");
        }
    } catch (PDOException $e) {
        header("Location: ../view_appointments.php?message=" . urlencode("An error occurred while deleting the appointment: " . $e->getMessage()) . "&type=alert");
    }
    exit();
} else {
    header("Location: ../index.php?message=You are not authorized to perform this operation");
    exit();
}