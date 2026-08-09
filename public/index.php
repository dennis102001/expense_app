<?php
session_start();

require_once '../app/core/Autoloader.php';

if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');

    if ($env !== false) {
        foreach ($env as $key => $value) {
            $_ENV[$key] = $value;
        }
    }
}

$appUrl = getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? '');

require_once '../app/routes.php';