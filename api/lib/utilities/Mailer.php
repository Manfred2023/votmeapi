<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../vendor/autoload.php';

function sendLoginTokenMail(string $toEmail, string $token, string $date, string $lastname): bool {
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host       = 'raven.o2switch.net';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@fredcode.net';
        $mail->Password   = 'Manfred500.';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Expéditeur
        $mail->setFrom('noreply@fredcode.net', 'VotMe');
        // Destinataire
        $mail->addAddress($toEmail);

        // Contenu du mail
        $mail->isHTML(true);
        $mail->Subject = "VOTRE JETON DE CONNEXION, $date";

        $mail->Body = "
            <p>Cher $lastname,</p>
            <p>Pour vous connecter, votre jeton est :</p>
            <h2>$token</h2>
            <p><strong>AVERTISSEMENT :</strong> Ce jeton est confidentiel.</p>
            <p>Connexion directe : 
                <a href='https://votme.com/fr/portal/login/{$token}.com'>
                    https://votme.com/fr/portal/login/{$token}.com
                </a>
            </p>
            <br>
            <p>Cordialement,<br>
            L’équipe VotMe+<br>
            <a href='https://votme.cm'>https://votme.cm</a><br>
            support@votme.cm</p>
        ";

        $mail->AltBody = "Cher $lastname,\n\n"
            . "Jeton : $token\n"
            . "Connexion directe : https://votme.com/fr/portal/login/{$token}.com\n\n"
            . "Cordialement,\nLa team VotMe+";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Erreur d'envoi de mail à $toEmail : {$mail->ErrorInfo}");
        return false;
    }
}
