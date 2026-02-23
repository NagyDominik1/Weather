<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailService {

    private static function getMailer() {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = '759446de6158ff';
        $mail->Password   = 'e3aa761bf1e6b6';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom('ee@stud.vts.su.ac.rs', 'WeatherBase Security');
        return $mail;
    }

    private static function getBaseUrl() {
        $host = $_SERVER['HTTP_HOST'];

        // LOCALHOST (XAMPP/WAMP)
        if ($host === 'localhost' || $host === '127.0.0.1') {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
            return "$protocol://$host/iws-2025-hu/Projekt-iws/public";
        }

        // EGYETEMI SZERVER
        // Itt nem használunk változót a protokollhoz, hogy véletlenül se legyen dupla https://
        return "https://ee.stud.vts.su.ac.rs/iws-2025-hu/Projekt-iws/public";
    }

    public static function sendActivationEmail($toEmail, $activationCode) {
        try {
            $mail = self::getMailer();
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Fiók aktiválása - WeatherBase';

            // A trim() biztonság kedvéért leszedi a felesleges perjeleket a széléről
            $baseUrl = rtrim(self::getBaseUrl(), '/');
            $link = $baseUrl . "/activate?code=" . urlencode($activationCode);

            $mail->Body = "
                <div style='font-family: sans-serif; border: 1px solid #e2e8f0; padding: 30px; border-radius: 15px; color: #1e293b; max-width: 500px;'>
                    <h1 style='color: #2563eb; font-size: 24px;'>Üdvözlünk a WeatherBase-ben!</h1>
                    <p>Kattints az alábbi gombra a fiókod aktiválásához:</p>
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='$link' style='background: #2563eb; color: white; padding: 12px 25px; text-decoration: none; border-radius: 10px; font-weight: bold; display: inline-block;'>Fiók aktiválása</a>
                    </div>
                    <p style='font-size: 11px; color: #94a3b8; word-break: break-all;'>Ha a gomb nem működik: $link</p>
                </div>";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Email hiba: " . $e->getMessage());
            return false;
        }
    }

    public static function sendPasswordReset($toEmail, $token) {
        try {
            $mail = self::getMailer();
            $mail->addAddress($toEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Jelszó visszaállítása - WeatherBase';

            $baseUrl = rtrim(self::getBaseUrl(), '/');
            $link = $baseUrl . "/reset-password?token=" . urlencode($token);

            $mail->Body = "
                <div style='font-family: sans-serif; padding: 20px; color: #334155;'>
                    <h2 style='color: #2563eb;'>Jelszó visszaállítás</h2>
                    <a href='$link' style='background: #2563eb; color: white; padding: 12px 24px; text-decoration: none; border-radius: 12px; font-weight: bold;'>Új jelszó beállítása</a>
                </div>";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Reset hiba: " . $e->getMessage());
            return false;
        }
    }

    public static function sendWeatherAlert($to, $subject, $htmlMessage) {
        try {
            $mail = self::getMailer();
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlMessage;
            return $mail->send();
        } catch (Exception $e) {
            error_log("Riasztás hiba: " . $e->getMessage());
            return false;
        }
    }
}