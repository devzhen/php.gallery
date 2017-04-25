<?php

namespace app\controllers;

use app\components\AuthenticationManager;

class ImageController extends Controller
{


    public function actionAdd($album_id = null)
    {

        if ($_SERVER['REQUEST_METHOD'] == "POST" && AuthenticationManager::isAuthenticated('admin')) {

            try {

                $album = $this->album_manager->findOne(\intval($album_id));

                /*Путь к директории, куда будут загружены изображения*/
                $path_to_upload_dir = BASE_DIR . "/app/web/upload-images/" . \intval($album->id) . "_" .
                    mb_strtolower($album->name, 'UTF-8') . "/";

                /*Создание менеджера загрузки изображений*/
                $imageUploadManager = new \app\components\ImageUploadManager();

                /*Загрузить изображения*/
                $result = $imageUploadManager->uploadToFileSystem($path_to_upload_dir);

                /*Результат загрузки*/
                $_SESSION['fileToUpload'] = [
                    "message" => $result
                ];

                /*Получение загруженных изображений*/
                $uploaded_images = $imageUploadManager->getUploadedImages();

                /*Сохранить в БД*/
                foreach ($uploaded_images as $img) {

                    $image = new \app\models\entities\Image();
                    $image->name = $img['name'];
                    $image->dir = $img['dir'];
                    $image->src = BASE_URL .
                        "/app/web/upload-images/" .
                        \intval($album->id) . "_" .
                        mb_strtolower($album->name, 'UTF-8') . "/" .
                        $img['uniqueId'] . "_" .
                        $img['name'];
                    $image->albumId = \intval($album_id);
                    $this->image_manager->save($image);
                }

                header('Location: ' . BASE_URL . '/admin/albums/' . $album_id);

            } catch (\app\components\exceptions\ImageUploadException $e) {

                $_SESSION['fileToUpload'] = [
                    "message" => $e->getMessage()
                ];

            } catch (\mysqli_sql_exception $e) {

                $this->action500($e->getMessage());
            }

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
