<?php
require 'db-config.php';

global $pdo;

if (isset($_GET['doctor_id']) && isset($_GET['date'])) {
    $doctor_id = intval($_GET['doctor_id']);
    $date = $_GET['date'];

    try {
        $stmt = $pdo->prepare("SELECT TIME(schedule) AS time FROM Appointment WHERE doctorID = :doctor_id AND DATE(schedule) = :date");
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        $bookedTimes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bookedTimes[] = $row['time'];
        }

        echo json_encode($bookedTimes);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode([]);
}