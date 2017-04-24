<?php

namespace app\components;


class AuthenticationManager
{

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