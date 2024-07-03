<?php
global $pdo;
session_start();
require 'db-config.php';
require '../vendor/autoload.php';

use Mailjet\Resources;

if (!isset($_SESSION['patientID'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['patientID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $procedure_id = $_POST['procedure_id'];
    $appointment_day = $_POST['appointment_day'];
    $appointment_time = $_POST['appointment_time'];


    $stmt = $pdo->prepare("SELECT Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday FROM DoctorSchedule WHERE doctorID = :doctor_id");
    $stmt->bindParam(':doctor_id', $doctor_id);
    $stmt->execute();
    $schedule = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$schedule) {
        $message = 'The doctor schedule cannot be found.';
        header("Location: ../appointment.php?message=" . urlencode($message));
        exit();
    }

    $day_of_week = date('w', strtotime($appointment_day));
    $days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    if (!$schedule[$days[$day_of_week]]) {
        $message = 'The chosen doctor does not work on that day.';
        header("Location: ../appointment.php?message=" . urlencode($message));
        exit();
    }


    $stmt2 = $pdo->prepare("SELECT worktime FROM Doctor WHERE doctorID = :doctor_id");
    $stmt2->bindParam(':doctor_id', $doctor_id);
    $stmt2->execute();
    $doctor = $stmt2->fetch(PDO::FETCH_ASSOC);

    if (!$doctor) {
        $message = 'The doctor cannot be found.';
        header("Location: ../appointment.php?message=" . urlencode($message));
        exit();
    }

    $worktime = explode('-', $doctor['worktime']);
    $start_time = strtotime($worktime[0]);
    $end_time = strtotime($worktime[1]);
    $selected_time = strtotime($appointment_time);

    if ($selected_time < $start_time || $selected_time >= $end_time || ($selected_time - $start_time) % 1800 !== 0) {
        $message = 'Invalid appointment.';
        header("Location: ../appointment.php?message=" . urlencode($message));
        exit();
    }


    $appointment_datetime = date('Y-m-d H:i:s', strtotime("$appointment_day $appointment_time"));
    $token = bin2hex(random_bytes(16)); // Generate a secure random token

    $stmt3 = $pdo->prepare("INSERT INTO Appointment (schedule, doctorID, patientID, procedureID, token) VALUES (:schedule, :doctor_id, :patient_id, :procedure_id, :token)");
    $stmt3->bindParam(':schedule', $appointment_datetime);
    $stmt3->bindParam(':doctor_id', $doctor_id);
    $stmt3->bindParam(':patient_id', $user_id);
    $stmt3->bindParam(':procedure_id', $procedure_id);
    $stmt3->bindParam(':token', $token);

    try {
        $stmt3->execute();

        // Send email with the token
        $mailjet = new \Mailjet\Client('de2b623e143d8108e9837c55fefad61c', 'ee3163ca1e706a216b0651c38792d30c', true, ['version' => 'v3.1']);
        $email = $_SESSION['email']; // Assuming the user's email is stored in the session

        $message = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "mlasavovicc@gmail.com",
                        'Name' => "MESZ Dentist Clinic"
                    ],
                    'To' => [
                        [
                            'Email' => $email,
                            'Name' => $_SESSION['firstName'] . ' ' . $_SESSION['lastName']
                        ]
                    ],
                    'Subject' => "Appointment confirmation.",
                    'TextPart' => "Dear Patient!\n\nYour appointment has been successfully booked\n\nAppointment: $appointment_datetime\n\nToken: $token\n\nPlease arrive on time!\n\nBest regards,\nMESZ Dentist Clinic",
                    'HTMLPart' => "<p>Dear Patient!</p><p>Your appointment has been successfully booked</p><p><strong>Appointment:</strong> $appointment_datetime</p><p><strong>Token:</strong> $token</p><p>Please arrive on time!</p><p>Best regards,<br>MESZ Dentist Clinic</p>"
                ]
            ]
        ];

        $response = $mailjet->post(Resources::$Email, ['body' => $message]);
        if ($response->success()) {
            $message = 'The appointment successfully booked, and the email sent.';
        } else {
            $message = 'The appointment successfully booked, but the email was not sent.';
        }

        header("Location: ../appointment.php?message=" . urlencode($message));
    } catch (PDOException $e) {
        if ($e->getCode() == 1062) {
            $message = 'The appointment has benn already booked.';
            header("Location: ../appointment.php?message=" . urlencode($message));
        } else {
            $message = 'An error occured while trying to make an appointment. Please, try again.';
            header("Location: ../appointment.php?message=" . urlencode($message));
        }
    }

    exit();
} else {
    $message = 'Invalid request method';
    header("Location: ../appointment.php?message=" . urlencode($message));
    exit();
}