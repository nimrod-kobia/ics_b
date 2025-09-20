<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

require_once __DIR__ . '/conf.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Initialize dependencies
$ObjFncs     = new GlobalFunctions();
$ObjForm     = new Forms($pdo);
$ObjLayout   = new Layouts();
$ObjSendMail = new SendMail($conf); // pass $conf if needed (SMTP settings)
$auth        = new Auth($pdo, $conf, $ObjSendMail);

// Default form (signin unless told otherwise)
$ObjFncs->showForm = $_GET['form'] ?? 'signin';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signin'])) {
        $auth->signin($ObjFncs);
        $ObjFncs->showForm = 'signin';
    } elseif (isset($_POST['signup'])) {
        $auth->signup($ObjFncs);
        $ObjFncs->showForm = 'signup';
    }
}

// Layout rendering
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);
$ObjLayout->form_content($conf, $ObjForm, $ObjFncs);
$ObjLayout->footer($conf);
