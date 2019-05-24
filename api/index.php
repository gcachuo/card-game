<?php
setcookie('XDEBUG_SESSION', 'PHPSTORM');
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function ($class) {
    $split = explode('\\', $class);
    $dir = $split[0];
    $file = ucfirst(isset_get($split[1]));
    $path = "$dir/$file.php";
    if (file_exists($path)) {
        include $path;
    }
});

include "Lib/System.php";
include "Lib/MySQL.php";

$request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$controller = strtolower($request[0]);
$action = isset_get($request[1]);
$response = null;

$namespace = "Controller\\$controller";
if (class_exists($namespace)) {
    $class = new $namespace();
    if (method_exists($class, $action)) {
        $response = $class->$action();
        set_response($response);
    }
}

set_response($response);