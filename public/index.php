<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/conf.php';

use App\Layouts\Layouts;
use App\Forms\Forms;
use App\Mail\SendMail;
use App\Auth\Auth;
use App\GlobalFunctions;

try {
    // Initialize core objects
    $ObjFncs     = new GlobalFunctions();
    $ObjForm     = new Forms($pdo);
    $ObjLayout   = new Layouts();
    $ObjSendMail = new SendMail($conf);
    $auth        = new Auth($pdo, $conf, $ObjSendMail);

    // Determine which form to show (default: signin)
    $ObjFncs->showForm = $_GET['form'] ?? 'signin';

    // Handle POST requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['signin'])) {
            $auth->signin($ObjFncs);
            $ObjFncs->showForm = 'signin';
        } elseif (isset($_POST['signup'])) {
            $auth->signup($ObjFncs);
            $ObjFncs->showForm = 'signup';
        }
    }

    // Render page layout
    $ObjLayout->header($conf);
    $ObjLayout->navbar($conf);
    $ObjLayout->banner($conf);
    $ObjLayout->form_content($conf, $ObjForm, $ObjFncs);
    $ObjLayout->footer($conf);

} catch (\Exception $e) {
    echo "<div class='container my-5'><p class='text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</p></div>";
}
