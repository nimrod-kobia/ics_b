<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendMail {
    public function Send_Mail(array $conf, array $mailCnt): bool {
        // Load Composer's autoloader (relative to project root)
        require_once __DIR__ . '/../vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = $conf['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $conf['smtp_user'];
            $mail->Password   = $conf['smtp_pass'];
            $mail->SMTPSecure = ($conf['smtp_port'] == 465)
                                ? PHPMailer::ENCRYPTION_SMTPS
                                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $conf['smtp_port'];

            // Recipients
            $mail->setFrom($mailCnt['mail_from'], $mailCnt['name_from']);
            $mail->addAddress($mailCnt['mail_to'], $mailCnt['name_to']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $mailCnt['subject'];
            $mail->Body    = $mailCnt['body'];

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
