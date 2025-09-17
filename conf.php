<?php
// Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Site Settings
$conf = [
    'site_timezone' => 'Africa/Nairobi',
    'site_name'     => 'ICS B Academy',
    'site_url'      => 'http://localhost/tol',
    'admin_email'   => 'admin@icsbacademy.com',
    'site_lang'     => 'en',

    // Database Settings
    'db_type' => 'pdo',
    'db_host' => '127.0.0.1',
    'db_name' => 'ics_b_users',
    'db_user' => 'icsbuser',              // or 'root'
    'db_pass' => 'StrongPassword123',

    // Email / SMTP Settings
    'mail_type'   => 'smtp',             // 'smtp' or 'mail'
    'smtp_host'   => 'smtp.gmail.com',
    'smtp_port'   => 587,                // use 465 for SSL, 587 for TLS
    'smtp_user'   => 'nimrodkobia066@gmail.com',
    'smtp_pass'   => 'yfon emhj jatk cmnh', // Gmail app password
    'smtp_secure' => 'tls',              // 'ssl' or 'tls'

    // App Rules
    'min_password_length' => 8,
    'valid_email_domain'  => [
        'icsbacademy.com',
        'yahoo.com',
        'gmail.com',
        'outlook.com',
        'hotmail.com',
        'strathmore.edu'
    ]
];

// Timezone
date_default_timezone_set($conf['site_timezone']);

// Database Connection
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
