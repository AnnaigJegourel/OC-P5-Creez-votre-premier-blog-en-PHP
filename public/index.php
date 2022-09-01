<?php

require_once "../vendor/autoload.php";

use Tracy\Debugger;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Debugger::enable();

$router = new App\Router();
$router -> run();

