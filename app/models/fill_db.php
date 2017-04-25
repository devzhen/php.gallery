<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

$dir = str_replace("\\app\\models", "", __DIR__);

define("BASE_DIR", $dir);
define("BASE_URL", "http://php.gallery");

require_once "entities/Album.php";
require_once "entities/Image.php";
require_once "managers/AlbumMySQLManager.php";
require_once "managers/ImageMySQLManager.php";

$album_manager = new \app\models\managers\AlbumMySQLManager(BASE_DIR . "/app/config/db.php");

/*Заполнить таблицу альбомы*/
for ($i = 1; $i <= 13; $i++) {

    /*Создать альбом*/
    $album = new \app\models\entities\Album();

    /*Имя альбома*/
    $album->name = $i . "_name";

    /*Описание альбома*/
    $album->description = $i . "_description";

    $date = new DateTime('now');
    $album->date = $date->format("Y-m-d H:i:s");

    /*Сохранить в БД*/
    $album = $album_manager->save($album);

    /*Создать директорию для изображений*/
    $path = BASE_DIR . "/app/web/upload-images/" . $album->id . "_" . mb_strtolower($album->name, 'UTF-8');
    if (!file_exists($path)) {
        \mkdir($path);
    }

    /*Заменить слэши*/
    $path = \str_replace("\\", "/", $path);

    /*Сохранить путь к директории альбома в БД*/
    $album_manager->update(
        $album,
        null,
        null,
        null,
        $path,
        null
    );
}
