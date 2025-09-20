<?php
declare(strict_types=1);
require 'conf.php';
require 'ClassAutoLoad.php';

$email = $_GET['email'] ?? '';
$code  = $_GET['code'] ?? '';

if ($email && $code) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :e AND verification_code = :c LIMIT 1");
    $stmt->execute([':e' => $email, ':c' => $code]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE users SET verified = 1 WHERE email = :e");
        $update->execute([':e' => $email]);
        echo "<h2>Account verified successfully! You can now <a href='signin.php'>sign in</a>.</h2>";
    } else {
        echo "<h2 style='color:red'>Invalid verification link or code.</h2>";
    }
} else {
    echo "<h2 style='color:red'>Missing verification details.</h2>";
}
