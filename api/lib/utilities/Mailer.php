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
        $mail->Subject = "JETON DE CONNEXION, $date";

        $mail->Body = "
        <div style='font-family: Arial, sans-serif; color: #333; background: #f4f6f8; padding: 20px;'>
            <table width='100%' style='max-width: 600px; margin: auto; background: #fff; border-radius: 8px; overflow: hidden;'>
                <tr style='background-color: #1a73e8; color: white;'>
                    <td style='padding: 15px; font-size: 18px;'>
                        🔐 Nouveau jeton de connexion VotMe
                    </td>
                </tr>
                <tr>
                    <td style='padding: 20px;'>
                        <h3 style='margin-top: 0;'>👤 Informations de l'utilisateur</h3>
                        <p><strong>Nom :</strong> $lastname</p>
                        <p><strong>Email :</strong> $toEmail</p>
                        <p><strong>Date :</strong> $date</p>
                        <hr>
                        <h3>🔑 Jeton de connexion</h3>
                        <p style='font-size: 20px; background: #f1f1f1; padding: 10px; border-radius: 6px; text-align: center; font-weight: bold;'>$token</p>
                        <p>⚠️ Ce jeton est personnel et confidentiel. Ne le partagez avec personne.</p>
                        <p>👉 Cliquez ici pour vous connecter directement :</p>
                        <p>  
                            <a href='http://localhost:5173/portal/login/{$token}' 
                               style='display: inline-block; padding: 10px 20px; background: #1a73e8; color: white; text-decoration: none; border-radius: 5px;'>
                                Accéder à mon espace
                            </a>
                        </p>
                        <hr>
                        <p style='font-size: 12px; color: #666;'>Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer ce message.</p>
                        <p style='font-size: 12px;'>L’équipe VotMe+<br>
                        <a href='https://votme.cm'>https://votme.cm</a><br>
                        support@votme.cm</p>
                    </td>
                </tr>
            </table>
        </div>";

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



/*function sendLoginTokenMail(string $toEmail, string $token, string $date, string $lastname): bool {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'raven.o2switch.net';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@fredcode.net';
        $mail->Password   = 'Manfred500.';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('noreply@fredcode.net', 'VotMe');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = "🔐 Votre jeton de connexion - $date";

        $mail->Body = "
        <div style='font-family: Arial, sans-serif; color: #333; background: #f4f6f8; padding: 20px;'>
            <table width='100%' style='max-width: 600px; margin: auto; background: #fff; border-radius: 8px; overflow: hidden;'>
                <tr style='background-color: #1a73e8; color: white;'>
                    <td style='padding: 15px; font-size: 18px;'>
                        🔐 Nouveau jeton de connexion VotMe
                    </td>
                </tr>
                <tr>
                    <td style='padding: 20px;'>
                        <h3 style='margin-top: 0;'>👤 Informations de l'utilisateur</h3>
                        <p><strong>Nom :</strong> $lastname</p>
                        <p><strong>Email :</strong> $toEmail</p>
                        <p><strong>Date :</strong> $date</p>
                        <hr>
                        <h3>🔑 Jeton de connexion</h3>
                        <p style='font-size: 20px; background: #f1f1f1; padding: 10px; border-radius: 6px; text-align: center; font-weight: bold;'>$token</p>
                        <p>⚠️ Ce jeton est personnel et confidentiel. Ne le partagez avec personne.</p>
                        <p>👉 Cliquez ici pour vous connecter directement :</p>
                        <p>
                            <a href='https://votme.com/fr/portal/login/{$token}.com' 
                               style='display: inline-block; padding: 10px 20px; background: #1a73e8; color: white; text-decoration: none; border-radius: 5px;'>
                                Accéder à mon espace
                            </a>
                        </p>
                        <hr>
                        <p style='font-size: 12px; color: #666;'>Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer ce message.</p>
                        <p style='font-size: 12px;'>L’équipe VotMe+<br>
                        <a href='https://votme.cm'>https://votme.cm</a><br>
                        support@votme.cm</p>
                    </td>
                </tr>
            </table>
        </div>";

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
}*/

