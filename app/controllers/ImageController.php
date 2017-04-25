<?php

namespace app\controllers;

use app\components\AuthenticationManager;

class ImageController extends Controller
{

    public function actionAdd($album_id = null)
    {

        if ($_SERVER['REQUEST_METHOD'] == "POST" && AuthenticationManager::isAuthenticated('admin')) {

            // Если не выбран файл
            if (\count($_FILES["fileToUpload"]['name']) == 0) {

                $_SESSION['fileToUpload'] = [
                    "message" => "File not selected."
                ];

            } else {

                try {

                    $album = $this->album_manager->findOne(\intval($album_id));

                } catch (\mysqli_sql_exception $e) {

                    $this->action500($e->getMessage());
                    return;
                }

                for ($i = 0; $i < \count($_FILES["fileToUpload"]['name']); $i++) {

                    /*Проверка размера изображения*/
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$i]);

                    /*Если не изображение*/
                    if ($check === false) {
                        continue;
                    }

                    /*Место расположения изображения*/
                    $uniqueId = uniqid();

                    $imageDir = BASE_DIR . "/app/web/upload-images/" . $album->id . "_" .
                        mb_strtolower($album->name, 'UTF-8') . "/" . $uniqueId . "_" . $_FILES["fileToUpload"]["name"][$i];

                    /*Путь, который будет указываться в атрибуте src. <img src ="$imageSrc" />*/
                    $imageSrc = BASE_URL . "/app/web/upload-images/" . $album->id . "_" .
                        mb_strtolower($album->name, 'UTF-8') . "/" . $uniqueId . "_" . $_FILES["fileToUpload"]["name"][$i];

                    /*Загрузка изображения*/
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $imageDir)) {

                        /* Загружено одно или несколько изображений*/
                        if (\count($_FILES["fileToUpload"]["tmp_name"]) > 1) {
                            $_SESSION['fileToUpload'] = [
                                "message" => "Uploaded " . \count($_FILES["fileToUpload"]["tmp_name"]) . " image (s)."
                            ];
                        } else {
                            $_SESSION['fileToUpload'] = [
                                "message" => "The file '" . basename($_FILES["fileToUpload"]["name"][$i]) . "' has been uploaded."
                            ];
                        }

                        // Сохранить в БД
                        $image = new \app\models\entities\Image();
                        $image->name = $_FILES["fileToUpload"]["name"][$i];
                        $image->src = $imageSrc;
                        $image->dir = $imageDir;
                        $image->albumId = \intval($album_id);

                        try {

                            $this->image_manager->save($image);

                        } catch (\mysqli_sql_exception $e) {

                            $_SESSION['fileToUpload'] = [
                                "message" => "Sorry, there was an error in mysql database uploading your file."
                            ];
                        }

                    } else {

                        $_SESSION['fileToUpload'] = [
                            "message" => "Sorry, there was an error uploading your file."
                        ];
                    }
                }

            }

            header('Location: ' . BASE_URL . '/admin/albums/' . $album_id);

        } else {

            $this->action404();
        }
    }

    public function actionDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && AuthenticationManager::isAuthenticated('admin')) {

            try {
                $image = $this->image_manager->findOne($_POST['imageId']);

                // Удаление изображения из БД
                $this->image_manager->delete($image->id);

                // Удаление изображения из каталога
                \unlink($image->dir);

                $_SESSION['fileToUpload'] = [
                    "message" => "Image '$image->name' was deleted"
                ];

            } catch (\mysqli_sql_exception $e) {

                echo "Error: " . $e->getMessage();
            }


        } else {
            $this->action404();
        }
    }

    public function actionUpdatePosition()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && AuthenticationManager::isAuthenticated('admin')) {

            // Изменить порядок вывода на экран для изображений
            $images = json_decode(file_get_contents('php://input'));

            foreach ($images as $i) {

                try {
                    $image = $this->image_manager->findOneBySrc($i->src);

                    if (!is_null($image)) {

                        $this->image_manager->update(
                            $image,
                            null,
                            null,
                            null,
                            null,
                            $i->position
                        );
                    }

                } catch (\mysqli_sql_exception $e) {

                    echo "MySQl Error: " . $e->getMessage();
                    return;
                }
            }

        } else {
            $this->action404();
        }
    }

}
