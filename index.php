<?php

session_start();
// Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Require autoload file
require("vendor/autoload.php");

// Instantiate F3
$f3 = Base::instance();

$f3->set('DEBUG', 3);

// Defining a default route
$f3->route('GET /', function () {
    $view = new Template();
    echo $view->render('views/home.html');
});

// Run F3
$f3->run();

