<?php

namespace app\components;

/**
 * Class AuthenticationManager
 * @package app\components
 */
class AuthenticationManager
{

    /**
     * Метод проверяет нужна ли аутентификация и если да - отправляет на страницу /login
     */
    public static function checkClientAuthentication($client)
    {
        if (!self::isAuthenticated($client)) {

            // Запись в сессию текущего url
            $_SESSION['relatedUrl'] = BASE_URL . $_SERVER['REQUEST_URI'];
            // Переадресация на страницу аутентификации
            header("Location: " . BASE_URL . "/login");
            return;

        }
    }

    public static function isAuthenticated($client)
    {
        if ($client == 'user') {
            return false;
        }

        session_start();

        if (isset($_SESSION['is_auth']) && $_SESSION['is_auth'] == true) {
            return true;
        }

        return false;
    }

    public static function setAuthentication($expr)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if ($expr) {
            $_SESSION['is_auth'] = true;
        } else {
            $_SESSION = [];

            // Удаление сессионных cookies
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            session_destroy();
        }
    }

}