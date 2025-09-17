<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once __DIR__ . '/../Forms/Forms.php';
require_once __DIR__ . '/../Global/Sendmail.php';

// Ensure $ObjForm, $ObjFncs exist (from ClassAutoLoad.php)
if (!isset($ObjForm)) $ObjForm = new Forms($pdo);
if (!isset($ObjFncs)) $ObjFncs = new stdClass();

// Determine which form is active
$formType = $showForm ?? ($_GET['form'] ?? 'signin');

### ------------------- SIGN IN -------------------
if ($formType === 'signin' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin_submit'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $ObjFncs->signin_msg = '';

    if ($email === '' || $password === '') {
        $ObjFncs->signin_msg = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $ObjFncs->signin_msg = "Please provide a valid email address.";
    } else {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $ObjFncs->signin_msg = "Sign in successful!";
        } else {
            $ObjFncs->signin_msg = "Invalid email or password.";
        }
    }
}

### ------------------- SIGN UP -------------------
if ($formType === 'signup' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup_submit'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $ObjFncs->signup_errors = [];
    $ObjFncs->signup_msg    = '';

    // Validate fullname
    if ($fullname === '' || !preg_match("/^[a-zA-Z ]*$/", $fullname)) {
        $ObjFncs->signup_errors['fullname'] = "Only letters and spaces allowed.";
    }

    // Validate email
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $ObjFncs->signup_errors['email'] = "Invalid email address.";
    }

    // Validate password length
    if (strlen($password) < $conf['min_password_length']) {
        $ObjFncs->signup_errors['password'] = "Password must be at least {$conf['min_password_length']} characters.";
    }

    // Check if email already exists
    if (empty($ObjFncs->signup_errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $ObjFncs->signup_errors['email'] = "Email already registered. Please sign in.";
        }
    }

    // Insert new user
    if (empty($ObjFncs->signup_errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$fullname, $email, $hashedPassword])) {
            // Send verification email
            $ObjSendMail = new SendMail();
            $mailCnt = [
                'name_from' => 'ICS B Academy',
                'mail_from' => $conf['smtp_user'],
                'name_to'   => $fullname,
                'mail_to'   => $email,
                'subject'   => 'Welcome! Verify Your Account',
                'body'      => "<p>Hello {$fullname},</p>
                                <p>Click to verify your account:</p>
                                <p><a href='{$conf['site_url']}/verify.php?email=" . urlencode($email) . "'>Verify Account</a></p>"
            ];
            $mailSent = $ObjSendMail->Send_Mail($conf, $mailCnt);

            $ObjFncs->signup_msg = $mailSent
                ? "Signup successful! Verification email sent."
                : "Account created but verification email failed.";
        } else {
            $ObjFncs->signup_msg = "Error: Could not create account.";
        }
    }
}
