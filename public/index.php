<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

date_default_timezone_set(config('app.timezone'));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->run();
