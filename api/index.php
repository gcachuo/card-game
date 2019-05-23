<?php
setcookie('XDEBUG_SESSION', 'PHPSTORM');
ini_set('display_errors', 1);
error_reporting(E_ALL);
spl_autoload_register(function ($class) {
    $split = explode('\\', $class);
    include "$split[0]/{$split[1]}.php";
});

include "lib/MySQL.php";

use Model\Cards;

new Cards();