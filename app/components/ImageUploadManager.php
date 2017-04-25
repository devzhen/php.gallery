<?php

namespace app\components;


class ImageUploadManager
{

    /*@var array $uploaded_images*/
    private $uploaded_images;

    /**
     * ImageUploadManager constructor.
     */
    public function __construct()
    {
        $this->uploaded_images = array();
    }

    /**
     * Method uploads images to file system
     *
     * @param string $path_to_dir The path to the directory where images will be uploaded.
     * @param \app\models\entities\Album $album Object of class 'Album', in which images will be uploaded
     * @throws exceptions\ImageUploadException
     * @return int | string Returns count or name of the uploaded image.
     */
    public function uploadToFileSystem($path_to_dir)
    {

        $returned_message = "The file is not an image";

        /*Если не выбрано изображение*/
        if (\count($_FILES["fileToUpload"]['name']) == 0) {
            throw new \app\components\exceptions\ImageUploadException("No image selected");
        }

        for ($i = 0; $i < \count($_FILES["fileToUpload"]['name']); $i++) {

            /*Проверка размера изображения*/
            $is_image = getimagesize($_FILES["fileToUpload"]["tmp_name"][$i]);

            /*Если не изображение*/
            if ($is_image === false) {
                continue;
            }

            /*Уникальный id*/
            $uniqueId = uniqid();

            /*Место расположения изображения*/
            $imageDir = $path_to_dir . $uniqueId . "_" . $_FILES["fileToUpload"]["name"][$i];


            /*Загрузка изображения*/
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $imageDir)) {

                /*Добавить сохраненное изображение в массив*/
                array_push($this->uploaded_images, array(

                    'name' => $_FILES["fileToUpload"]["name"][$i],
                    'uniqueId' => $uniqueId,
                    'dir' => $imageDir
                ));


                /* Загружено одно или несколько изображений*/
                if (\count($_FILES["fileToUpload"]["tmp_name"]) > 1) {

                    /*Вернуть кол-во загруженных изображений*/
                    $returned_message =  "Uploaded " . \count($_FILES["fileToUpload"]["tmp_name"]) . " image (s).";

                } else {

                    /*Вернуть имя загруженного изображения*/
                    $returned_message =  "The file '" . basename($_FILES["fileToUpload"]["name"][$i]) . "' has been uploaded.";
                }

            } else {

                throw new \app\components\exceptions\ImageUploadException("There was an error uploading your image.");
            }
        }

        return $returned_message;

    }

    /**
     * Method returns images loaded into the file system
     *
     * @return array
     */
    public function getUploadedImages()
    {
        return $this->uploaded_images;
    }


}