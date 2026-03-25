<?php
session_start();

require_once '../app/core/Autoloader.php';
require_once __DIR__ . '/../vendor/autoload.php';

$env = parse_ini_file(__DIR__.'/../.env');

foreach($env as $key => $value){
    $_ENV[$key] = $value;
    
}

require_once '../app/routes.php';