<?php
require_once __DIR__ . '/ClassAutoLoad.php';

$showForm = 'signup';  // tell auth.php we want signup
require_once __DIR__ . '/Proc/auth.php';

$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);

// Make sure $ObjFncs->showForm is set for Layouts
$ObjFncs->showForm = 'signup';

$ObjLayout->form_content($conf, $ObjForm, $ObjFncs);
$ObjLayout->footer($conf);
