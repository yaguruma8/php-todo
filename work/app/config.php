<?php

session_start();

define('DSN', 'mysql:host=db;dbname=myapp;charset=utf8mb4');
define('DB_USER', 'myappuser');
define('DB_PASS', 'myapppass');
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);

// require_once(__DIR__ . '/Utils.php');
// require_once(__DIR__ . '/Token.php');
// require_once(__DIR__ . '/Database.php');
// require_once(__DIR__ . '/Todo.php');

spl_autoload_register(function ($class) {
    $filename = __DIR__ . "/$class.php";
    if (file_exists($filename)) {
        require ($filename);
    } else {
        echo "file not found: $filename";
        exit;
    }
});
