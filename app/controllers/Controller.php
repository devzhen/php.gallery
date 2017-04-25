<?php

namespace app\controllers;


class Controller
{
    protected $view = null;
    protected $album_manager = null;
    protected $image_manager = null;

    public function __construct()
    {
        $this->view = new \app\components\View();
        $this->view->layout = 'layouts/album_layout';

        $config = include(BASE_DIR . '/app/config/db.php');

        $this->album_manager = new \app\models\managers\AlbumMySQLManager($config);
        $this->image_manager = new \app\models\managers\ImageMySQLManager($config);

    }

    public function action404()
    {
        $this->view->render('404');
    }

    public function action500($error_message = null)
    {
        $this->view->render('500', array('error_message' => $error_message));
    }
}