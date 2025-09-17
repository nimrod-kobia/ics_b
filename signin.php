<?php
require_once __DIR__ . '/ClassAutoLoad.php';

$showForm = 'signin';
require_once __DIR__ . '/Proc/auth.php';

$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);

$ObjFncs->showForm = 'signin';

$ObjLayout->form_content($conf, $ObjForm, $ObjFncs);
$ObjLayout->footer($conf);
