<?php

spl_autoload_register(function (string $class) {
    $class = ltrim($class, '\\');

    if (str_starts_with($class, 'App\\')) {
        $class = substr($class, strlen('App\\'));
    }

    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
