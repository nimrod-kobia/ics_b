<?php
declare(strict_types=1);

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site Settings
$conf = [
    'site_timezone' => 'Africa/Nairobi',
    'site_name'     => 'ICS B Academy',
    'site_url' => 'http://localhost/ics_b-1',
    'admin_email'   => 'admin@icsbacademy.com',
    'site_lang'     => 'en',

    // Database Settings
    'db_type' => 'pdo',
    'db_host' => '127.0.0.1',
    'db_name' => 'ics_b_users',
    'db_user' => getenv('DB_USER') ?: 'icsbuser',  // fallback if env not set
    'db_pass' => getenv('DB_PASS') ?: 'StrongPassword123',

    // Email / SMTP Settings
    'mail_type'   => 'smtp',  // 'smtp' or 'mail'
    'smtp_host'   => 'smtp.gmail.com',
    'smtp_port'   => 587,     // 465 for SSL, 587 for TLS
    'smtp_user'   => getenv('SMTP_USER') ?: 'nimrodkobia066@gmail.com',
    'smtp_pass'   => getenv('SMTP_PASS') ?: 'yfon emhj jatk cmnh',
    'smtp_secure' => 'tls',   // 'ssl' or 'tls'

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

// Language strings
$lang = [
    'signup_success' => 'Your account has been created successfully.',
    'signup_error'   => 'There was a problem creating your account.',
    'signin_error'   => 'Invalid email or password.',
    'required_field' => 'Please fill out all required fields.'
];

// Timezone
date_default_timezone_set($conf['site_timezone']);
// Database Connection
try {
    $dsn = sprintf(
        "mysql:host=%s;dbname=%s;charset=utf8mb4",
        $conf['db_host'],
        $conf['db_name']
    );

    $pdo = new \PDO($dsn, $conf['db_user'], $conf['db_pass'], [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES   => false, // safer queries
    ]);
} catch (\PDOException $e) {
    // In production: log error, don't display sensitive info
    die("Database connection failed. Please try again later.");
}
