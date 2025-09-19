<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/conf.php';
require_once __DIR__ . '/ClassAutoLoad.php';

// Initialize Layouts
$ObjLayout = new Layouts();

// Override banner text for this page
$bannerConf = $conf;
$bannerConf['banner_title']    = 'Registered Users';
$bannerConf['banner_subtitle'] = 'Manage all users who have signed up for ICS 2.2';

$msg = "";

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $msg = "<div class='alert alert-warning'>Email already registered!</div>";
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO users (name, email, password, created_at, verified) 
                 VALUES (?, ?, ?, NOW(), 1)" //  auto-verified
            );
            $stmt->execute([$name, $email, $pass]);
            $msg = "<div class='alert alert-success'>User added successfully!</div>";
        }
    } catch (PDOException $e) {
        $msg = "<div class='alert alert-danger'>Error adding user: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// Handle Delete User (via POST only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $id = (int) $_POST['delete_user'];
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "<div class='alert alert-warning'>User deleted successfully!</div>";
    } catch (PDOException $e) {
        $msg = "<div class='alert alert-danger'>Error deleting user: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// Fetch users
$users = [];
try {
    $stmt = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $msg = "<div class='alert alert-danger mt-3'>Error fetching users: " . htmlspecialchars($e->getMessage()) . "</div>";
}

// Render layout
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($bannerConf);
?>

<div class="container my-5">
    <h2 class="mb-4 text-center fw-bold"><i class="bi bi-people-fill"></i> Manage Users</h2>

    <?php if (!empty($msg)) echo $msg; ?>

    <!-- Add User Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title fw-bold"><i class="bi bi-person-plus-fill"></i> Add New User</h5>
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

    <!-- User List -->
    <?php if (empty($users)): ?>
        <div class="alert alert-info text-center">No users have signed up yet.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($users as $user): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm rounded-4 p-3 h-100">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($user['name']) ?></h5>
                            <p class="card-text mb-2"><i class="bi bi-envelope-fill"></i> <?= htmlspecialchars($user['email']) ?></p>
                            <span class="badge bg-secondary">
                                Joined: <?= htmlspecialchars($user['created_at']) ?>
                            </span>
                            <div class="mt-3">
                                <form method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="delete_user" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$ObjLayout->footer($conf);
