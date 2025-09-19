<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendMail {
    public function send(array $conf, array $mailCnt): bool {
        require_once __DIR__ . '/../vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            // Debug (use SMTP::DEBUG_SERVER to see details)
            $mail->SMTPDebug = SMTP::DEBUG_OFF;

            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = $conf['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $conf['smtp_user'];
            $mail->Password   = $conf['smtp_pass'];
            $mail->SMTPSecure = $conf['smtp_secure'] === 'ssl'
                                ? PHPMailer::ENCRYPTION_SMTPS
                                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int) $conf['smtp_port'];

            // Sender
            $mail->setFrom($conf['smtp_user'], $mailCnt['name_from'] ?? $conf['site_name']);

            // Recipient
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
