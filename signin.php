<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload classes
require 'ClassAutoLoad.php';

// Render page sections
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);
$ObjLayout->form_content($conf, $ObjForm); // Display the sign-in form
$ObjLayout->footer($conf); // End of signin.php
