<?php

namespace core;

use PDO;

class Database {
    public static function connect(){

        $dbHost = getenv('DB_HOST') ?: $_ENV['DB_HOST'];
        $dbPort = getenv('DB_PORT') ?: $_ENV['DB_PORT'];
        $dbName = getenv('DB_NAME') ?: $_ENV['DB_NAME'];
        $dbUser = getenv('DB_USER') ?: $_ENV['DB_USER'];
        $dbPass = getenv('DB_PASS') ?: $_ENV['DB_PASS'];

        return new PDO(
            'mysql:host=' . $dbHost . 
            ';port=' . $dbPort .
            ';dbname='. $dbName .
            ';charset=utf8',
            $dbUser,
            $dbPass
        );
    }
}