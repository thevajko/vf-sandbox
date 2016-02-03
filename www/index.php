<?php

//show PHP error and notifications
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Loading base object for boot up whole application
require_once "../scripts/vajko/core/Booter.php";

//create application
$booter = new vajko\core\Booter(true);
//initialize the booter
$booter->run();

