<?php
$conf = [
    // Database settings
    'db_host' => '127.0.0.1',
    'db_name' => 'ics_b_users',
    'db_user' => 'icsbuser',       // or 'root'
    'db_pass' => 'StrongPassword123',

    // SMTP settings
    'smtp_host' => 'smtp.gmail.com',       // Gmail SMTP server
    'smtp_port' => 587,                    // TLS port
    'smtp_user' => 'nimrodkobia066@gmail.com', // your Gmail
    'smtp_pass' => 'yfon emhj jatk cmnh'       // your Gmail app password
];

try {
    $pdo = new \PDO(
        "mysql:host={$conf['db_host']};dbname={$conf['db_name']};charset=utf8",
        $conf['db_user'],
        $conf['db_pass']
    );
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
