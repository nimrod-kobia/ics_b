<?php
require_once __DIR__ . '/conf.php';
require_once __DIR__ . '/ClassAutoLoad.php';

$ObjFncs   = new GlobalFunctions();
$ObjLayout = new Layouts();
$auth      = new Auth($pdo, $conf);

$ObjLayout->header($conf);
$ObjLayout->navbar($conf);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $userId = $_SESSION['pending_user_id'] ?? null;
    $code   = trim($_POST['code']);

    if ($userId && $auth->verify2FA($ObjFncs, $userId, $code)) {
        echo "<div class='alert alert-success'>2FA verified! You are now logged in.</div>";
        // Redirect to dashboard or home
        header("Refresh:2; url=index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Invalid or expired code.</div>";
    }
}
?>

<div class="container my-5">
    <h2>Enter 2FA Code</h2>
    <form method="post">
        <div class="mb-3">
            <label for="code" class="form-label">Verification Code</label>
            <input type="text" name="code" id="code" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Verify</button>
    </form>
</div>

<?php $ObjLayout->footer($conf); ?>