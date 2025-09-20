<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../config/conf.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Layouts\Layouts;

try {
    $ObjLayout = new Layouts();
    $ObjLayout->header($conf);
    $ObjLayout->navbar($conf);
    $ObjLayout->banner($conf);

    $msg   = '';
    $email = '';
    $code  = '';

    // Determine if verification comes from GET (link click) or POST (manual entry)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])) {
        $email = trim($_POST['email'] ?? '');
        $code  = trim($_POST['code'] ?? '');
    } else {
        $email = trim($_GET['email'] ?? '');
        $code  = trim($_GET['code'] ?? '');
    }

    if ($email && $code) {
        $stmt = $pdo->prepare("SELECT id, verified, verification_code FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $msg = "<div class='alert alert-danger'>Invalid email address.</div>";
        } elseif ($user['verified']) {
            $msg = "<div class='alert alert-success'>Your account is already verified.</div>";
        } elseif ($user['verification_code'] !== $code) {
            $msg = "<div class='alert alert-danger'>Invalid verification code.</div>";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET verified = 1, verification_code = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            $msg = "<div class='alert alert-success'>Account verified successfully! You may now <a href='signin.php'>sign in</a>.</div>";
        }
    }

    echo "<div class='container my-5'><h2>Account Verification</h2>{$msg}</div>";

    // Always show manual verification form for convenience
    echo <<<HTML
    <div class='container my-3'>
        <div class='card p-4' style='max-width:500px;margin:auto;'>
            <h5>Enter your verification code manually</h5>
            <form method='post'>
                <div class='mb-3'>
                    <label>Email</label>
                    <input type='email' name='email' class='form-control' value='" . htmlspecialchars($email) . "' required>
                </div>
                <div class='mb-3'>
                    <label>Verification Code</label>
                    <input type='text' name='code' class='form-control' required>
                </div>
                <button type='submit' name='verify' class='btn btn-primary'>Verify</button>
            </form>
        </div>
    </div>
HTML;

    $ObjLayout->footer($conf);

} catch (\Exception $e) {
    echo "<div class='container my-5 text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
