<?php

session_start();

define('DSN', 'mysql:host=db;dbname=myapp;charset=utf8mb4');
define('DB_USER', 'myappuser');
define('DB_PASS', 'myapppass');
define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);

spl_autoload_register(function ($class) {
    $prefix = 'Myapp\\';
    if (strpos($class, $prefix) === 0) {
      $classname = substr($class, strlen($prefix));
      $filename = __DIR__ . "/$classname.php";
      if (file_exists($filename)) {
          require ($filename);
      } else {
          echo "file not found: $filename";
          exit;
      }
    }
});
