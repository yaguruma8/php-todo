<?php

namespace Myapp;

class Database
{

    private static $instance;

    public static function getInstance()
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }
        try {
            self::$instance = new \PDO(
                DSN,
                DB_USER,
                DB_PASS,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
            return self::$instance;
        } catch (\PDOException $e) {
            $e->getMessage();
            exit;
        }
    }

}
