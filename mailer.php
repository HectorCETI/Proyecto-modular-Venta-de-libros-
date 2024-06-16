<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function enviarCorreoActivacion($correo_institucional, $nombre, $codigo_activacion) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'mail.smtp2go.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hector.alvarez2922@alumnos.udg.mx';
        $mail->Password = 'YZYfV2q9aqKI1Wtz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente
        $mail->setFrom('no-reply@udg.mx', 'UniBooks UDG');

        // Destinatario
        $mail->addAddress($correo_institucional, $nombre);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Activación de cuenta';
        $mail->Body = "
            <h2>Hola $nombre,</h2>
            <p>Gracias por registrarte en UniBooks UDG. Por favor, usa el siguiente código para activar tu cuenta:</p>
            <p><b>Código de activación: $codigo_activacion</b></p>
            <p>Saludos,<br>El equipo de UniBooks UDG</p>
        ";
        $mail->AltBody = "Hola $nombre,\n\nGracias por registrarte en UniBooks UDG. Por favor, usa el siguiente código para activar tu cuenta:\n\nCódigo de activación: $codigo_activacion\n\nSaludos,\nEl equipo de UniBooks UDG";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
