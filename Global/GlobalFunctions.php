<?php
/**
 * Global helper functions for ICS B Academy
 */
class GlobalFunctions
{
    public $showForm = false; // explicitly declare, prevents deprecated warning

    private $messages = [];

    public function __construct()
    {
        // Ensure sessions are started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Sanitize user input
     */
    public function sanitizeInput(string $data): string
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate random verification code
     */
    public function generateVerificationCode(int $length = 6): string
    {
        return strtoupper(bin2hex(random_bytes($length / 2)));
    }

    /**
     * Set a flash message (errors, success, etc.)
     */
    public function setMsg(string $key, $message, string $type = 'info'): void
    {
        $_SESSION[$key] = [
            'msg'  => $message,
            'type' => $type
        ];
    }

    /**
     * Get a flash message
     */
    public function getMsg(string $key): ?array
    {
        if (isset($_SESSION[$key])) {
            $msg = $_SESSION[$key];
            unset($_SESSION[$key]); // clear after reading
            return $msg;
        }
        return null;
    }

    /**
     * Validate email address
     */
    public function validateEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Hash password securely
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verify password against hash
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
