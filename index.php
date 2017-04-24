<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define("BASE_DIR", dirname(__FILE__));
define("BASE_URL", "http://php.gallery");

spl_autoload_register(function ($class) {
    require_once $class . ".php";
});

// Получение маршрутов
$routesPath = BASE_DIR . "/app/config/routes.php";
$routes = include($routesPath);

$router = new app\components\Router($routes);
$router->run();