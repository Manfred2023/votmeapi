<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../vendor/autoload.php';

function sendLoginTokenMail(string $toEmail, string $token, string $date, string $lastname): bool {
    $mail = new PHPMailer(true);

    try {
        // Configuration serveur SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'moukatemanfred@gmail.com';
        $mail->Password   = 'uras ygyh xike aibq'; // 🔒 Astuce : mets ça dans un fichier .env !
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Expéditeur
        $mail->setFrom('moukatemanfred@gmail.com', 'VotMe');
        // Destinataire
        $mail->addAddress($toEmail);

        // Contenu de l'e-mail
        $mail->isHTML(true);
        $mail->Subject = "VOTRE JETON DE CONNEXION, $date";

        $mail->Body = "
            <p>Cher $lastname,</p>
        
            <p>Pour vous connecter, votre jeton est :</p>
            <h2>$token</h2>
        
            <p><strong>AVERTISSEMENT :</strong> Votre jeton est hautement confidentiel. Vous risquez d'être victime d'une grave usurpation d'identité s'il est divulgué.</p>
        
            <p>Vous pouvez vous connecter directement en cliquant sur le lien ci-dessous :</p>
            <p><a href='https://votme.com/fr/portal/login/{$token}.com'>https://votme.com/fr/portal/login/{$token}.com</a></p>
        
            <br>
            <p>Cordialement,<br>
            La team VotMe+<br>
            <a href='https://votme.cm'>https://votme.cm</a><br>
            Email : support@votme.cm</p> 
        ";

        $mail->AltBody = "Cher $lastname,\n\n"
            . "Pour vous connecter, votre jeton est : $token\n\n"
            . "AVERTISSEMENT : Votre jeton est hautement confidentiel. Vous risquez une grave usurpation d'identité s'il est divulgué.\n\n"
            . "Connexion directe : https://votme.com/fr/portal/login/{$token}.com\n\n"
            . "Cordialement,\nLa team VotMe+\nhttps://votme.cm\nsupport@votme.cm";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi de mail à $toEmail : {$mail->ErrorInfo}");
        return false;
    }
}
