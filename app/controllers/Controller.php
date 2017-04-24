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
        $this->album_manager = new \app\models\managers\AlbumMySQLManager();
        $this->image_manager = new \app\models\managers\ImageMySQLManager();
        $this->view->layout = 'layouts/album_layout';
    }

    public function action404()
    {
        $this->view->render('404');
    }
}