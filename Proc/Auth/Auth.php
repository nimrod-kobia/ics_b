<?php
namespace App\Auth;

use PDO;
use App\Mail\SendMail;
use App\GlobalFunctions;

class Auth
{
    private PDO $pdo;
    private array $conf;
    private SendMail $mailer;

    public function __construct(PDO $pdo, array $conf, SendMail $mailer)
    {
        $this->pdo    = $pdo;
        $this->conf   = $conf;
        $this->mailer = $mailer;
    }

    /**
     * Sign up a new user
     */
    public function signup(GlobalFunctions $ObjFncs): void
    {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validation
        if ($name === '' || $email === '' || $password === '') {
            $ObjFncs->setMsg('msg', 'All fields are required.', 'danger');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $ObjFncs->setMsg('msg', 'Invalid email address.', 'danger');
            return;
        }

        // Hash password and generate 6-digit verification code
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $code   = random_int(100000, 999999);

        // Insert user as unverified
        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password, verification_code, verified)
            VALUES (:name, :email, :password, :code, 0)
        ");
        $stmt->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $hashed,
            ':code'     => $code,
        ]);

        // Verification link
        $verificationLink = rtrim($this->conf['site_url'], '/')
                            . '/public/verify_2fa.php?email=' . urlencode($email)
                            . '&code=' . urlencode((string)$code);

        // Email content
        $mailContent = [
            'name_from' => $this->conf['site_name'],
            'mail_to'   => $email,
            'name_to'   => $name,
            'subject'   => 'Verify Your Account',
            'body'      => "<p>Hello " . htmlspecialchars($name) . ",</p>
                            <p>Your verification code is <strong>{$code}</strong></p>
                            <p>Click this link to verify your account automatically: <a href='{$verificationLink}'>Verify My Account</a></p>"
        ];

        if ($this->mailer->send($mailContent)) {
            $ObjFncs->setMsg('msg', 'Signup successful! Check your email for verification.', 'success');
        } else {
            $ObjFncs->setMsg('msg', 'Signup successful, but the verification email failed to send.', 'warning');
        }
    }

    /**
     * Sign in an existing user
     */
    public function signin(GlobalFunctions $ObjFncs): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $ObjFncs->setMsg('msg', 'Both email and password are required.', 'danger');
            return;
        }

        $stmt = $this->pdo->prepare("SELECT id, name, password, verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $ObjFncs->setMsg('msg', 'Email not found.', 'danger');
            return;
        }

        if (!password_verify($password, $user['password'])) {
            $ObjFncs->setMsg('msg', 'Incorrect password.', 'danger');
            return;
        }

        if (!$user['verified']) {
            $ObjFncs->setMsg('msg', 'Please verify your account first via email.', 'warning');
            return;
        }

        // Set session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        $ObjFncs->setMsg('msg', 'Signed in successfully!', 'success');
    }
}
