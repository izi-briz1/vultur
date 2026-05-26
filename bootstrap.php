<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

if (($_ENV['APP_ENV'] ?? 'prod') === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
}