<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Load config first so $conf, $lang, $pdo are available
require_once __DIR__ . '/conf.php';

// Ensure session works everywhere
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader for classes in subdirectories
$directories = ['Layouts', 'Forms', 'Global', 'Proc'];

spl_autoload_register(function ($class) use ($directories) {
    foreach ($directories as $dir) {
        $file = __DIR__ . '/' . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Instantiate core objects
$ObjLayout   = new Layouts();
$ObjFncs     = new GlobalFunctions();
$ObjSendMail = new SendMail();
$ObjAuth     = new Auth($pdo);   // pass PDO into constructor
$ObjForm     = new Forms($pdo);  //  Forms also needs PDO

// Handle auth actions based on script + request
$currentScript = basename($_SERVER['SCRIPT_NAME']);

if ($currentScript === 'signup.php' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ObjAuth->signup($conf, $ObjFncs, $ObjSendMail);   // no $pdo here
}

if ($currentScript === 'signin.php' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ObjAuth->signin($ObjFncs, $ObjSendMail); // Pass both arguments
}
