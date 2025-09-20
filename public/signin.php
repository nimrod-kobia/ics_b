<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/conf.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Layouts\Layouts;
use App\Auth\Auth;
use App\Mail\SendMail;
use App\GlobalFunctions;

try {
    // Layout and helper
    $ObjLayout = new Layouts();
    $ObjFncs   = new GlobalFunctions();
    $mailer    = new SendMail($conf);

    $ObjLayout->header($conf);
    $ObjLayout->navbar($conf);
    $ObjLayout->banner($conf);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth = new Auth($pdo, $conf, $mailer);
        $auth->signin($ObjFncs); // Process sign-in
    }

    // Display messages if any
    echo "<div class='container my-5'>";
    echo $ObjFncs->getMsg('msg'); // Shows any success/error messages
    echo "</div>";

    // Sign-in form
    $emailValue = htmlspecialchars($_POST['email'] ?? '');
    echo <<<HTML
    <div class="container my-4" style="max-width:400px;">
        <div class="card p-4">
            <h4 class="mb-3">Sign In</h4>
            <form method="post">
                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required value="{$emailValue}">
                </div>
                <div class="mb-3">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>
            <p class="mt-3 text-center">
                Don't have an account? <a href="signup.php">Sign Up</a>
            </p>
        </div>
    </div>
HTML;

    $ObjLayout->footer($conf);

} catch (\Exception $e) {
    echo "<div class='container my-5'><p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p></div>";
}
