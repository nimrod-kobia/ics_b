<?php
// Autoload classes
require 'ClassAutoLoad.php';

// Render page sections using the layout object
$ObjLayout->header($conf);
$ObjLayout->navbar($conf);
$ObjLayout->banner($conf);
$ObjLayout->content($conf);
$ObjLayout->footer($conf); // End of index.php
