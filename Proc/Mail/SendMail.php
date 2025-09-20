<?php
namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendMail
{
    private array $conf;

    public function __construct(array $conf)
    {
        $this->conf = $conf;
    }

    public function send(array $mailContent): bool
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = $this->conf['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->conf['smtp_user'];
            $mail->Password   = $this->conf['smtp_pass'];
            $mail->SMTPSecure = $this->conf['smtp_secure'] === 'ssl'
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int) $this->conf['smtp_port'];

            $mail->setFrom($this->conf['smtp_user'], $mailContent['name_from'] ?? $this->conf['site_name']);
            $mail->addAddress($mailContent['mail_to'], $mailContent['name_to'] ?? '');

            $mail->isHTML(true);
            $mail->Subject = $mailContent['subject'] ?? ($this->conf['site_name'] . ' Notification');
            $mail->Body    = $mailContent['body'] ?? '<p>This is a test email.</p>';

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            echo "<p style='color:red;'>Mailer Error: {$mail->ErrorInfo}</p>";
            return false;
        }
    }
}
