<?php
//show PHP error and notifications
error_reporting(E_ALL);
ini_set('display_errors', 1);

// create application
// If you want to use composer autoload object just comment out require_once that loads boote.php
// and set input parameter of thevajko\vf\core\Booter to null => new thevajko\vf\core\Booter();

    // Loading base object for boot up whole application
    require_once "../vendor/thevajko/vf-core/thevajko/vf/core/Booter.php";

    //creating booter instance
    $booter = new thevajko\vf\core\Booter([
                            __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."vendor",
                            __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."app"
                        ]);

    //initialize the booter
    $booter->run();

