<?php
/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configuration serveur SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'moukatemanfred@gmail.com';
    $mail->Password   = 'uras ygyh xike aibq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Informations sur l'email
    $mail->setFrom('ton.email@gmail.com', 'Manfred');
    $mail->addAddress('fredcode237@gmail.com', 'Fredcode');

    $mail->isHTML(true);
    $mail->Subject = 'Test SMTP via PHPMailer';
    $mail->Body    = '<b>Ceci est un test d\'envoi SMTP avec PHPMailer.</b>';
    $mail->AltBody = 'Ceci est la version texte du message.';

    $mail->send();
    echo 'Email envoyé avec succès.';
} catch (Exception $e) {
    echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
}*/
