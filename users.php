<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Load DB config
require_once __DIR__ . '/conf.php';

// Fetch users
$users = [];
try {
    $stmt = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Registered Users</h1>
    <?php if (empty($users)): ?>
        <p>No users have signed up yet.</p>
    <?php else: ?>
        <ol class="list-group list-group-numbered">
            <?php foreach ($users as $user): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($user['name']) ?></strong> 
                        (<?= htmlspecialchars($user['email']) ?>)
                    </div>
                    <small class="text-muted">Joined: <?= htmlspecialchars($user['created_at']) ?></small>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</div>
</body>
</html>
