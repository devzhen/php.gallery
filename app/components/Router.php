<?php

namespace app\components;

class Router
{
    private $routes = null;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    /**
     * Return request string
     * @return string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI']);
//            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        $url = $this->getURI();

        // Анализ маршрутов
        foreach ($this->routes as $route => $path) {

            if (preg_match("#^$route$#", $url)) {

                $internalRoute = preg_replace("#^$route$#", $path, $url);

                $segments = explode("/", $internalRoute);

                // Получение имени контроллера
                $controllerName = array_shift($segments) . "Controller";
                $controllerName = ucfirst($controllerName);

                // Получение имени action контроллера
                $actionName = 'action' . ucfirst(array_shift($segments));

                // Создание объекта контороллера
                $controllerName = "\\app\\controllers\\" . $controllerName;
                $controllerObject = new $controllerName();

                // Вызов action контроллера с параметрами, если они есть
                if (method_exists($controllerObject, $actionName)) {
                    call_user_func_array(array($controllerObject, $actionName), $segments);
                    return;
                }
            }
        }

        $controller = new \app\controllers\Controller();
        $controller->action404();
//        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    }
}