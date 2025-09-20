<?php
namespace App\Global;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendMail
{
    public function send(array $conf, array $mailCnt): bool
    {
        // Composer autoloader is already loaded in public/index.php
        $mail = new PHPMailer(true);

        try {
            // Debugging off in production
            $mail->SMTPDebug = SMTP::DEBUG_OFF;

            // SMTP config
            $mail->isSMTP();
            $mail->Host       = $conf['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $conf['smtp_user'];
            $mail->Password   = $conf['smtp_pass'];
            $mail->SMTPSecure = $conf['smtp_secure'] === 'ssl'
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int) $conf['smtp_port'];

            // Sender & recipient
            $mail->setFrom($conf['smtp_user'], $mailCnt['name_from'] ?? $conf['site_name']);
            $mail->addAddress($mailCnt['mail_to'], $mailCnt['name_to'] ?? '');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $mailCnt['subject'] ?? ($conf['site_name'] . ' Notification');
            $mail->Body    = $mailCnt['body'] ?? '<p>This is a test email.</p>';

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
