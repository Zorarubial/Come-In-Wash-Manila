<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/PHPMailer/vendor/autoload.php'; // Include PHPMailer library

function sendEmail($recipient, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dcruzbazilprems@gmail.com'; // Your Gmail address

        
        $mail->Password = 'serebii.net'; // Your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('dcruzbazilprems@gmail.com', 'Come In Wash Manila');
        $mail->addAddress($recipient);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Email sending failed
    }
}
?>
