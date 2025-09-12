<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'conf.php';
require 'ClassAutoLoad.php';

use App\Layouts;

// Database connection
try {
    $pdo = new PDO(
        "mysql:host={$conf['db_host']};dbname={$conf['db_name']};charset=utf8",
        $conf['db_user'],
        $conf['db_pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Instantiate layout
$ObjLayout = new Layouts();

// Render page header, navbar, and banner
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);

// Fetch users
try {
    $stmt = $pdo->query("SELECT name, email FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll();
} catch (\PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}

// Display numbered list
echo "<div class='container'><h3>Registered Users</h3><ol>";
foreach ($users as $user) {
    echo "<li>{$user['name']} ({$user['email']})</li>";
}
echo "</ol></div>";

// Footer
$ObjLayout->footer($conf);
