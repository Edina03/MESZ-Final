<?php
require '../vendor/autoload.php';
require 'db-config.php';
global $pdo;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT patientID, email FROM Patient WHERE email = ?");
    $stmt->execute([$email]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare("SELECT doctorID, email FROM Doctor WHERE email = ?");
    $stmt2->execute([$email]);
    $doctor = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($patient || $doctor) {
        $token = bin2hex(random_bytes(16));

        if ($patient) {
            $stmt = $pdo->prepare("UPDATE Patient SET forgot = ? WHERE email = ?");
            $stmt->execute([$token, $email]);
        } elseif ($doctor) {
            $stmt2 = $pdo->prepare("UPDATE Doctor SET forgot = ? WHERE email = ?");
            $stmt2->execute([$token, $email]);
        }

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'in-v3.mailjet.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'de2b623e143d8108e9837c55fefad61c';
        $mail->Password = 'ee3163ca1e706a216b0651c38792d30c';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('milasavovicc@gmail.com', 'MESZ Dentist Clinic');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Subject = 'Change password';
        $mail->Body = "<p>Dear user!</p>
                       <p>Click on the next link to reset your password:</p>
                       <p><a href='https://mesz.stud.vts.su.ac.rs/mesz/reset_password.php?token=$token'>Change password</a></p>
                       <p>If you did not request a password reset, please ignore this message.</p>";

        if (!$mail->send()) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            header("Location: ../request_reset.php?message=" . urlencode($mail->ErrorInfo) . "&type=alert");
        } else {
            header("Location: ../request_reset.php?message=" . urlencode("Check your email to reset your password.") . "&type=success");
        }
    } else {
        header("Location: ../request_reset.php?message=" . urlencode("This email address cannot be found.") . "&type=alert");
    }
    exit();
} else {
    header("Location: ../index.php?message=" . urlencode("Invalid request.") . "&type=alert");
    exit();
}