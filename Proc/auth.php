<?php
declare(strict_types=1);

class Auth {
    private \PDO $db;
    private array $conf;

    public function __construct(\PDO $db, array $conf = []) {
        $this->db   = $db;
        $this->conf = $conf;
    }

    /**
     * Handle user signup
     */
    public function signup(array $conf, GlobalFunctions $fn, SendMail $mailer): void {
        $fullname = $fn->sanitizeInput($_POST['fullname'] ?? '');
        $email    = $fn->sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($fullname) || empty($email) || empty($password)) {
            echo "<p style='color:red'>All fields are required.</p>";
            return;
        }

        if (!$fn->validateEmail($email)) {
            echo "<p style='color:red'>Invalid email address.</p>";
            return;
        }

        // Check for duplicate email
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo "<p style='color:red'>Email already registered.</p>";
            return;
        }

        $hash = $fn->hashPassword($password);

        try {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password, verified) VALUES (?, ?, ?, 0)");
            $stmt->execute([$fullname, $email, $hash]);
            echo "<p style='color:green'>Account created successfully. Please check your email.</p>";
            // Send verification email here (see below)
        } catch (\PDOException $e) {
            echo "<p style='color:red'>Database error: " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Handle signin → starts 2FA process
     */
    public function signin(GlobalFunctions $fn, SendMail $mailer): void {
        $email    = $fn->sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $fn->setMsg('error', 'Email and password are required.', 'danger');
            return;
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !$fn->verifyPassword($password, $user['password'])) {
            $fn->setMsg('error', 'Invalid email or password.', 'danger');
            return;
        }

        // Clean up old codes
        $this->db->prepare("DELETE FROM user_2fa WHERE user_id = ? AND expires_at < NOW()")->execute([$user['id']]);

        // Generate 2FA code
        $code = $fn->generateVerificationCode(6);

        // Save code in DB (expires in 5 minutes)
        $stmt = $this->db->prepare("INSERT INTO user_2fa (user_id, code, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))");
        $stmt->execute([$user['id'], $code]);

        // Send email
        $mailCnt = [
            'mail_to'   => $user['email'],
            'name_to'   => $user['name'],
            'subject'   => 'Your ICS B 2FA Code',
            'body'      => "<p>Hello {$user['name']},</p>
                            <p>Your verification code is:</p>
                            <h2>{$code}</h2>
                            <p>This code will expire in 5 minutes.</p>"
        ];
        $mailSent = $mailer->send($this->conf, $mailCnt);

        if (!$mailSent) {
            $fn->setMsg('error', 'Failed to send 2FA code. Please contact support.', 'danger');
            return;
        }

        // Store session for verification step
        $_SESSION['pending_user_id'] = $user['id'];

        // Redirect to 2FA verification
        header("Location: verify_2fa.php");
        exit;
    }

    /**
     * Verify 2FA code and log in user
     */
    public function verify2FA(GlobalFunctions $fn, int $userId, string $code): bool {
        $stmt = $this->db->prepare("SELECT * FROM user_2fa WHERE user_id = ? AND code = ? AND verified = 0 AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
        $stmt->execute([$userId, $code]);
        $row = $stmt->fetch();

        if ($row) {
            // Mark code as used
            $this->db->prepare("UPDATE user_2fa SET verified = 1 WHERE id = ?")->execute([$row['id']]);

            //  Successful login
            $_SESSION['user_id'] = $userId;
            unset($_SESSION['pending_user_id']);
            return true;
        }

        return false;
    }
}
