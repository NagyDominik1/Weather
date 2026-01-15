<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    public static function sendActivationEmail($toEmail, $activationCode) {
        $mail = new PHPMailer(true);
        try {
            // Mailtrap beállítások (a sajátjaidat írd be!)
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'A_TE_MAILTRAP_USERED';
            $mail->Password = 'A_TE_MAILTRAP_PASSOD';

            $mail->setFrom('no-reply@weatherapp.com', 'Weather App');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Regisztráció aktiválása';
            $activationLink = "http://localhost/iws-2025-hu/Projekt-iws/public/activate?code=" . $activationCode;
            $mail->Body = "Kattints ide az aktiváláshoz: <a href='$activationLink'>$activationLink</a>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}