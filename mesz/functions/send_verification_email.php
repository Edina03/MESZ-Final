<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

function send_verification_email($email, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'in-v3.mailjet.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'de2b623e143d8108e9837c55fefad61c';
        $mail->Password = 'ee3163ca1e706a216b0651c38792d30c';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('milasavovicc@gmail.com', 'MESZ Dentist Clinic');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Confirm email';
        $verificationLink = "https://mesz.stud.vts.su.ac.rs/mesz/verify.php?token=$token";
        $mail->Body = "Please, confirm your email at the following address: <a href='$verificationLink'>Confirm email</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

?>
