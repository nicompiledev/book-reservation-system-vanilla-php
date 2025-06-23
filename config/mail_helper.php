<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendConfirmationEmail($email, $username, $token) {
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // o el host de tu proveedor
        $mail->SMTPAuth = true;
        $mail->Username = 'nsrf95@gmail.com';
        $mail->Password = 'fhwu bjwx furf awdz'; // usa una contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('nsrf95@gmail.com', 'Book Reservation');
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Confirm your account';
        $mail->Body = "Hello <b>$username</b>,<br><br>
        Please confirm your account by clicking the link below:<br><br>
        <a href='http://localhost/book_reservation/verify.php?token=$token'>Confirm Account</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Mailer error: " . $mail->ErrorInfo;
        exit;
    }
}
