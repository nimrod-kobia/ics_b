<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class Sendmail {

    public function Send_Mail(array $conf, array $mailCnt): bool {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $conf['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $conf['smtp_user'];
            $mail->Password   = $conf['smtp_pass'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $conf['smtp_port'];

            // Recipients
            $mail->setFrom($mailCnt['mail_from'], $mailCnt['name_from']);
            $mail->addAddress($mailCnt['mail_to'], $mailCnt['name_to']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $mailCnt['subject'];
            $mail->Body    = $mailCnt['body'];

            return $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
