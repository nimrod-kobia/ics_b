<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

require_once __DIR__ . '/../config/conf.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Mail\SendMail;
use App\Layouts\Layouts;
use App\GlobalFunctions;

$ObjFncs   = new GlobalFunctions();
$ObjLayout = new Layouts();

// Render page layout
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail_submit'])) {
        $fullname = $ObjFncs->sanitizeInput($_POST['fullname'] ?? '');
        $email    = $ObjFncs->sanitizeInput($_POST['email'] ?? '');

        if ($fullname === '' || $email === '') {
            throw new \Exception('Please provide both fullname and email.');
        }

        if (!$ObjFncs->validateEmail($email)) {
            throw new \Exception('Invalid email address.');
        }

        // Generate a secure token for verification
        $token = bin2hex(random_bytes(16));
        $verificationLink = $conf['site_url'] . '/verify_2fa.php?token=' . urlencode($token);

        $mailContent = [
            'name_from' => $conf['site_name'] ?? 'ICS 2.2',
            'mail_to'   => $email,
            'name_to'   => $fullname,
            'subject'   => 'Account Verification',
            'body'      => "<p>Hello {$fullname},</p>
                            <p>Click <a href='{$verificationLink}'>here</a> to verify your account.</p>"
        ];

        $ObjSendMail = new SendMail($conf);
        if ($ObjSendMail->send($mailContent)) {
            $ObjFncs->setMsg('msg', "Verification email sent to {$email}.", 'success');
        } else {
            $ObjFncs->setMsg('msg', "Failed to send verification email.", 'danger');
        }
    }
} catch (\Exception $e) {
    $ObjFncs->setMsg('msg', $e->getMessage(), 'danger');
}

// Display the form and messages
?>
<div class="container my-5">
    <?php
    if ($msg = $ObjFncs->getMsg('msg')) {
        echo "<div class='alert alert-{$msg['type']}'>{$msg['msg']}</div>";
    }
    ?>
    <h2>Send Verification Email</h2>
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" name="fullname" id="fullname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <button type="submit" name="mail_submit" class="btn btn-primary">Send Verification Email</button>
    </form>
</div>
<?php
$ObjLayout->footer($conf);
