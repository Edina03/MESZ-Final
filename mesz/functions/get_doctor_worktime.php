<?php
require 'db-config.php';

global $pdo;

if (isset($_GET['doctor_id'])) {
    $doctor_id = intval($_GET['doctor_id']);

    try {
        // Fetch worktime from Doctor table
        $stmt = $pdo->prepare("SELECT worktime FROM Doctor WHERE doctorID = :doctor_id");
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt->execute();
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch schedule from DoctorSchedule table
        $stmt2 = $pdo->prepare("SELECT Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday FROM DoctorSchedule WHERE doctorID = :doctor_id");
        $stmt2->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt2->execute();
        $schedule = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($doctor && $schedule) {
            $worktime = explode('-', $doctor['worktime']);
            $start_time = $worktime[0];
            $end_time = $worktime[1];

            echo json_encode([
                'start' => $start_time,
                'end' => $end_time,
                'days' => $schedule
            ]);
        } else {
            echo json_encode(['error' => 'Doctor not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
}
?>
