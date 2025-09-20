<?php
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

    public function signup(GlobalFunctions $ObjFncs): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
            $name     = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($name === '' || $email === '' || $password === '') {
                $ObjFncs->setMsg('msg', 'All fields are required.', 'danger');
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $ObjFncs->setMsg('msg', 'Invalid email address.', 'danger');
                return;
            }

            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $code   = random_int(100000, 999999); // 6-digit code

            // Insert user
            $stmt = $this->pdo->prepare("
                INSERT INTO users (name, email, password, verification_code, verified) 
                VALUES (:n, :e, :p, :c, 0)
            ");
            $stmt->execute([
                ':n' => $name,
                ':e' => $email,
                ':p' => $hashed,
                ':c' => $code
            ]);

            // Prepare email
            $mailCnt = [
                'name_from' => $this->conf['site_name'],
                'mail_to'   => $email,
                'name_to'   => $name,
                'subject'   => 'Verify your account',
                'body'      => "
                    <p>Hello {$name},</p>
                    <p>Thank you for registering. Use the code below to verify your account:</p>
                    <h2>{$code}</h2>
                    <p>Or click this link:</p>
                    <a href='{$this->conf['site_url']}/verify_2fa.php?email=" . urlencode($email) . "&code={$code}'>Verify My Account</a>
                "
            ];

            if ($this->mailer->send($this->conf, $mailCnt)) {
                $ObjFncs->setMsg('msg', 'Signup successful! Please check your email to verify your account.', 'success');
            } else {
                $ObjFncs->setMsg('msg', 'Signup successful, but verification email failed to send.', 'warning');
            }
        }
    }

    public function signin(GlobalFunctions $ObjFncs): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                $ObjFncs->setMsg('msg', 'Email and password are required.', 'danger');
                return;
            }

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :e LIMIT 1");
            $stmt->execute([':e' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                $ObjFncs->setMsg('msg', 'Invalid email or password.', 'danger');
                return;
            }

            if (!$user['verified']) {
                $ObjFncs->setMsg('msg', 'Please verify your account first.', 'warning');
                return;
            }

            // Store session
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            $ObjFncs->setMsg('msg', 'Login successful. Welcome back!', 'success');
        }
    }
}
