<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require 'conf.php';
require 'ClassAutoLoad.php';
require_once __DIR__ . '/Layouts/Layouts.php';
require_once __DIR__ . '/Forms/Forms.php';
require_once __DIR__ . '/Global/Sendmail.php';

// Create Layout object
$ObjLayout = new Layouts();
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);

// Show signup form
$form = new Forms();
$form->signup();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    if ($username === '' || $email === '') {
        echo "<p style='color:red'>Please provide both username and email.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red'>Please provide a valid email address.</p>";
    } else {
        // Email contents
        $mailCnt = [
            'name_from' => 'ICS 2.2',
            'mail_from' => $conf['smtp_user'],
            'name_to'   => $username,
            'mail_to'   => $email,
            'subject'   => 'Welcome to ICS 2.2! Account Verification',
            'body'      => "
                <p>Hello {$username},</p>
                <p>You requested an account on ICS 2.2.</p>
                <p>To complete your registration, 
                   <a href='https://yourdomain.com/verify.php?user=" . urlencode($username) . "'>Click Here</a>.
                </p>
                <br>
                Regards,<br>
                Systems Admin<br>
                ICS 2.2
            "
        ];

        // Send email
        $ObjSendMail = new Sendmail();

        if ($ObjSendMail->Send_Mail($conf, $mailCnt)) {
            echo "<p style='color:green'>Verification email sent successfully to <strong>{$email}</strong>.</p>";
        } else {
            echo "<p style='color:red'>Failed to send verification email. Please try again.</p>";
        }
    }
}

// Footer
$ObjLayout->footer($conf);
