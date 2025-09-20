<?php
// public/signin.php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start(); // required for storing user/2FA state

require_once __DIR__ . '/../config/conf.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Load classes (autoloaded via Composer or your ClassAutoLoad if still in use)
require_once __DIR__ . '/../src/Layouts/Layouts.php';
require_once __DIR__ . '/../src/Forms/Forms.php';
require_once __DIR__ . '/../src/Global/GlobalFunctions.php';
require_once __DIR__ . '/../src/Global/SendMail.php';
require_once __DIR__ . '/../src/Auth/Auth.php';

// Initialize dependencies
$ObjFncs     = new GlobalFunctions();
$ObjForm     = new Forms($pdo);
$ObjLayout   = new Layouts();
$ObjSendMail = new SendMail();
$auth        = new Auth($pdo, $conf);

// Handle signin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->signin($ObjFncs, $ObjSendMail);
    // 🔹 Make sure Auth->signin() does header("Location: ..."); exit; on success
}

// Layout rendering
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);

// Tell Layouts which form to show
$ObjFncs->showForm = 'signin';
$ObjLayout->form_content($conf, $ObjForm, $ObjFncs);

$ObjLayout->footer($conf);
