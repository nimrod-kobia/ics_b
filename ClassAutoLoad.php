<?php
require 'conf.php';

$directories = ['Layouts', 'Forms', 'Global', 'Proc'];

spl_autoload_register(function ($class) use ($directories) {
    foreach ($directories as $dir) {
        $file = __DIR__ . '/' . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Use PDO instead of mysqli
$ObjLayout = new Layouts();
$ObjForm   = new Forms($pdo);
