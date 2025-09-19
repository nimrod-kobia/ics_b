<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/conf.php';
require_once __DIR__ . '/ClassAutoLoad.php';

// Initialize dependencies
$ObjFncs     = new GlobalFunctions();
$ObjForm     = new Forms($pdo);     
$ObjLayout   = new Layouts();
$ObjSendMail = new SendMail();      
$auth        = new Auth($pdo);      

// Run signup if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth->signup($conf, $ObjFncs, $ObjSendMail);
}

// Layout rendering
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);

$ObjFncs->showForm = 'signup';  // tell Layouts which form to show
$ObjLayout->form_content($conf, $ObjForm, $ObjFncs);

$ObjLayout->footer($conf);
