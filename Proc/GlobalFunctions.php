<?php
namespace App;

class GlobalFunctions
{
    public $showForm = 'signin';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function sanitizeInput(string $data): string
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public function generateVerificationCode(int $length = 6): string
    {
        return strtoupper(bin2hex(random_bytes((int)($length / 2))));
    }

    public function setMsg(string $key, $message, string $type = 'info'): void
    {
        $_SESSION[$key] = ['msg' => $message, 'type' => $type];
    }

    public function getMsg(string $key): ?array
    {
        if (isset($_SESSION[$key])) {
            $msg = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $msg;
        }
        return null;
    }

    public function validateEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
