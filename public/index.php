<?php

require_once "../vendor/autoload.php";
require_once "../config/db.php";

use Tracy\Debugger;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Debugger::enable();

$router = new App\Router();
$router -> run();

