<?php

namespace app\controllers;

use app\components\AuthenticationManager;

class LoginController extends Controller
{
    public function actionLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($_POST['client_name'] == 'admin' && $_POST['client_password'] == 'admin') {

                AuthenticationManager::setAuthentication(true);

                /*URL с которого произошло перенаправление*/
                if (isset($_SESSION['relatedUrl'])) {
                    $url = $_SESSION['relatedUrl'];
                    unset($_SESSION['relatedUrl']);
                } else {
                    $url = BASE_URL . "/admin";
                }

                header("Location: " . $url);


            } else {
                if (session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                $_SESSION['auth_message'] = "Wrong data";
                header("Location: " . BASE_URL . "/login");
            }

        } else {
            $this->view->render('login');
        }

    }

    public function actionLogout()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            AuthenticationManager::setAuthentication(false);

            echo json_encode(array("redirect" => BASE_URL));
        } else {

            AuthenticationManager::setAuthentication(false);
            header("Location: " . BASE_URL);
        }

    }
}