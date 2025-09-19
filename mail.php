<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require 'conf.php';
require 'ClassAutoLoad.php';
require_once __DIR__ . '/Layouts/Layouts.php';
require_once __DIR__ . '/Forms/Forms.php';
require_once __DIR__ . '/Global/SendMail.php';

// Create Layout object
$ObjLayout = new Layouts();
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);
?>

<h2>Send Verification Email</h2>
<form action="" method="post" autocomplete="off">
  <div class="mb-3">
    <label for="fullname" class="form-label">Fullname</label>
    <input type="text" class="form-control" id="fullname" name="fullname" maxlength="50" placeholder="Enter your fullname" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <button type="submit" class="btn btn-primary" name="mail_submit">Send Verification Email</button>
</form>

<?php
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail_submit'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    if ($fullname === '' || $email === '') {
        echo "<p style='color:red'>Please provide both fullname and email.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red'>Please provide a valid email address.</p>";
    } else {
        // Email contents
        $mailCnt = [
            'name_from' => 'ICS 2.2',
            'mail_from' => $conf['smtp_user'],
            'name_to'   => $fullname,
            'mail_to'   => $email,
            'subject'   => 'Welcome to ICS 2.2! Account Verification',
            'body'      => "
                <p>Hello {$fullname},</p>
                <p>You requested an account on ICS 2.2.</p>
                <p>To complete your registration, 
                 <a href='http://localhost/ics_b-1/signup.php?user=" . urlencode($fullname) . "'>Verify Your Account</a>
                </p>
                <br>
                Regards,<br>
                Systems Admin<br>
                ICS 2.2
            "
        ];

        // Send email
        $ObjSendMail = new SendMail();

        if ($ObjSendMail->send($conf, $mailCnt)) {
            echo "<p style='color:green'>Verification email sent successfully to <strong>{$email}</strong>.</p>";
        } else {
            echo "<p style='color:red'>Failed to send verification email. Please try again.</p>";
        }
    }
}

// Footer
$ObjLayout->footer($conf);
