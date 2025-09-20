<?php
// public/mail.php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../config/conf.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Global/SendMail.php';
require_once __DIR__ . '/../src/Layouts/Layouts.php';

$ObjLayout = new Layouts();
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail_submit'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    if ($fullname === '' || $email === '') {
        echo "<p style='color:red'>Please provide both fullname and email.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red'>Please provide a valid email address.</p>";
    } else {
        // Generate a verification token (safer than passing name)
        $token = bin2hex(random_bytes(16));

        // Example: store token + email in database here for later verification

        $verificationLink = $conf['site_url'] . '/verify_2fa.php?token=' . urlencode($token);

        $mailCnt = [
            'name_from' => $conf['app_name'] ?? 'ICS 2.2',
            'mail_from' => $conf['smtp_user'],
            'name_to'   => $fullname,
            'mail_to'   => $email,
            'subject'   => 'Welcome to ' . ($conf['app_name'] ?? 'ICS 2.2') . '! Account Verification',
            'body'      => "
                <p>Hello " . htmlspecialchars($fullname) . ",</p>
                <p>Click the link below to verify your account:</p>
                <p><a href='{$verificationLink}'>Verify Your Account</a></p>
            "
        ];

        $ObjSendMail = new SendMail();
        if ($ObjSendMail->send($conf, $mailCnt)) {
            echo "<p style='color:green'>Verification email sent to <strong>" . htmlspecialchars($email) . "</strong>.</p>";
        } else {
            echo "<p style='color:red'>Failed to send verification email.</p>";
        }
    }
}
?>

<h2>Send Verification Email</h2>
<form action="" method="post" autocomplete="off">
    <div class="mb-3">
        <label for="fullname" class="form-label">Fullname</label>
        <input type="text" class="form-control" id="fullname" name="fullname" maxlength="50" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <button type="submit" class="btn btn-primary" name="mail_submit">Send Verification Email</button>
</form>

<?php $ObjLayout->footer($conf); ?>
