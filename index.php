<?php


// Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Require autoload file
require("vendor/autoload.php");
session_start();
// Instantiate F3
$f3 = Base::instance();

$f3->set('DEBUG', 3);

//Instantiate database
$db = new Database();
$controller = new Controller($f3);

// Defining a default route
$f3->route('GET /', function () {
    $GLOBALS['controller']->home();
});

$f3->route('GET|POST /login', function () {
    $GLOBALS['controller']->login();
});

$f3->route('GET|POST /signup', function () {
    $GLOBALS['controller']->signup();
});

$f3->route('GET /logout', function () {
    $GLOBALS['controller']->logout();
});

$f3->route('GET /view/@header', function () {
    $GLOBALS['controller']->viewPost($GLOBALS['f3']->get('PARAMS.header'));
});
$f3->route('GET /category/@category', function () {
    $GLOBALS['controller']->category($GLOBALS['f3']->get('PARAMS.category'));
});



// Run F3
$f3->run();

