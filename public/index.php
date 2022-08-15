<?php

require_once "../vendor/autoload.php";

use Tracy\Debugger;

Debugger::enable();

$router = new App\Router();
$router -> run();

