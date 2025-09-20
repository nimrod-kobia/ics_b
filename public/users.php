<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

require_once __DIR__ . '/../config/conf.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Layouts\Layouts;
use App\GlobalFunctions;

try {
    $ObjLayout = new Layouts();
    $ObjFncs   = new GlobalFunctions();

    $bannerConf = $conf;
    $bannerConf['banner_title']    = 'Registered Users';
    $bannerConf['banner_subtitle'] = 'Manage all signed-up users';

    // Handle Add User
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
        $name  = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $pass === '') {
            $ObjFncs->setMsg('msg', 'All fields are required.', 'danger');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $ObjFncs->setMsg('msg', 'Invalid email address.', 'warning');
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $ObjFncs->setMsg('msg', 'Email already registered!', 'warning');
            } else {
                $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare(
                    "INSERT INTO users (name, email, password, created_at, verified) 
                     VALUES (?, ?, ?, NOW(), 1)"
                );
                $stmt->execute([$name, $email, $hashedPass]);
                $ObjFncs->setMsg('msg', 'User added successfully!', 'success');
            }
        }
    }

    // Handle Delete User
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
        $id = (int) ($_POST['delete_user'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $ObjFncs->setMsg('msg', 'User deleted successfully!', 'warning');
        }
    }

    // Fetch all users
    $stmt  = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Render page
    $ObjLayout->header($conf);
    $ObjLayout->navbar($conf);
    $ObjLayout->banner($bannerConf);

    echo '<div class="container my-5">';
    echo '<h2 class="text-center mb-4">Manage Users</h2>';

    // Show flash messages
    if ($msg = $ObjFncs->getMsg('msg')) {
        echo "<div class='alert alert-{$msg['type']}'>{$msg['msg']}</div>";
    }

    // Add User Form
    ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Add New User</h5>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
            </form>
        </div>
    </div>
    <?php

    // List users
    if (empty($users)) {
        echo "<div class='alert alert-info'>No users found.</div>";
    } else {
        echo "<div class='row g-4'>";
        foreach ($users as $user) {
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm p-3">
                    <h5><?= htmlspecialchars($user['name']) ?></h5>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                    <span>Joined: <?= htmlspecialchars($user['created_at']) ?></span>
                    <form method="post" class="mt-2" onsubmit="return confirm('Delete this user?');">
                        <input type="hidden" name="delete_user" value="<?= $user['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <?php
        }
        echo "</div>";
    }

    echo '</div>'; // container
    $ObjLayout->footer($conf);

} catch (\Exception $e) {
    echo "<div class='container my-5'><p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p></div>";
}
