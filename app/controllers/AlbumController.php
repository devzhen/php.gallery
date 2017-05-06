<?php

namespace app\controllers;


class AlbumController extends Controller
{

    /**
     * This action displays a list of all albums
     * @param string $client - Админ или пользователь проссматривает страницу?
     * @param integer $page - Номер страницы если есть постраничная навигация
     */
    public function actionAll($client = 'user', $page = 1)
    {

        /*HTML код pagination кнопок*/
        $paginationButtons = null;

        /*Max кол-во pagination - кнопок на странице*/
        $countPaginationButton = 5;

        /*Кол-во альбомов на странице*/
        $albumLimit = 12;

        /*Параметр OFFSET , кот.будет исп-ся в sql-запросе SELECT ... LIMIT ...OFFSET*/
        $albumOffset = 0;

        /*Если клиент не администратор - необходима постраничная навигация*/
        if ($client == 'user') {


            /*Количество альбомов*/
            try {

                $albumCount = $this->album_manager->count();

            } catch (\mysqli_sql_exception $e) {
                $this->action500();
                return;
            }

            /*Создать пагинацию только если альбомы созданы*/
            if ($albumCount > 0) {

                /*Создание объекта Pagination, кот. расчитывает кол-во страниц и
                параметр OFFSET для sql-запроса SELECT*/
                $p = new \app\components\PaginationManager($albumCount, $albumLimit, $countPaginationButton);

                /*Получение параметра OFFSET для sql-запроса SELECT*/
                $albumOffset = $p->getOffset($page);

                /*Если параметр $page - некорректный*/
                if (is_null($albumOffset)) {

                    $this->action404();
                    return;

                }

                /*Получение HTML кода pagination buttons*/
                $paginationButtons = $p->getPaginationButtons($page);
            }


            /*Получение списка альбомов*/
            try {

                $albums = $this->album_manager->findAll($albumLimit, $albumOffset);

            } catch (\mysqli_sql_exception $e) {

                $this->action500();
                return;
            }


        } elseif ($client == 'admin') {

            // Проверка - нужна ли аутентификация
            \app\components\AuthenticationManager::checkClientAuthentication($client);

            if (isset($_SESSION['last_created'])) {
                unset($_SESSION['last_created']);
            }
            if (isset($_SESSION['fileToUpload']['message'])) {
                unset($_SESSION['fileToUpload']['message']);
            }

            /*Получение списка альбомов*/
            try {
                $albums = $this->album_manager->findAll();
            } catch (\mysqli_sql_exception $e) {
                $this->action500($e->getMessage());
                return;
            }

        }

        // Добавление в каждый альбом первой фотографии
        foreach ($albums as &$album) {

            // Установка hash token
            $album = $album + array('token' => md5(uniqid(rand(), true)));

            // Установка первого изображения в альбоме
            $firstImage = $this->image_manager->findAllByAlbum(\intval($album['id']), 1);

            if (empty($firstImage)) {
                $album = $album + array('firstImage' => BASE_URL . "/app/web/images/no-image.jpg");
            } else {
                $album = $album + array('firstImage' => $firstImage[0]['src']);
            }
        }


        $this->view->render('albums', array('albums' => $albums, 'client' => $client, 'paginationButtons' => $paginationButtons));
    }

    /**
     * This action creates а new album
     */
    public function actionNew()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            try {
                $album = new \app\models\entities\Album();

                /*Название альбома*/
                $album->name = trim($_POST['aName']);

                /*Дата создания*/
                $timezone = new \DateTimeZone($_POST['aTimezone']);
                $date = new \DateTime($_POST['aDate']);
                $date->setTimezone($timezone);
                $album->date = $date->format("Y-m-d H:i:s");

                /*Описание*/
                $album->description = trim($_POST['aDescription']);


                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }

                /*Поиск в БД(если уже существует - запретить)*/
                if (!is_null($this->album_manager->findByName($album->name))) {

                    $_SESSION['last_created'] = "<h2 style='color: red'>The name '$album->name' is already in use.</h2>";

                } else {

                    /*Сохранить в БД*/
                    $album = $this->album_manager->save($album);

                    /*Создать директорию для изображений*/
                    $path = BASE_DIR . "/app/web/upload-images/" . $album->id . "_" . mb_strtolower($album->name, 'UTF-8');
                    if (!file_exists($path)) {
                        \mkdir($path);
                    }

                    /*Заменить слэши*/
                    $path = \str_replace("\\", "/", $path);

                    /*Сохранить путь к директории в БД*/
                    $this->album_manager->update(
                        $album,
                        null,
                        null,
                        null,
                        $path,
                        null
                    );

                    $_SESSION['last_created'] = "<h3>The album '$album->name' was created.</h3>";
                }

                header('Location: ' . BASE_URL . $_SERVER['REQUEST_URI']);

            } catch (\mysqli_sql_exception $e) {
                $this->action500($e->getMessage());
            }

        } else {

            // Нужна ли аутентификация
            if (!\app\components\AuthenticationManager::isAuthenticated('admin')) {

                // Запись в сессию url, с кот. произошло перенаправление
                $_SESSION['relatedUrl'] = BASE_URL . $_SERVER['REQUEST_URI'];
                // Переадресация на страницу аутентификации
                header("Location: " . BASE_URL . "/login");
                return;
            }

            $this->view->render('new');
        }
    }

    /**
     * This action displays one album
     * @param integer $id
     * @param string $client
     */
    public function actionOne($id = null, $client = 'user')
    {

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $client == 'admin' && \app\components\AuthenticationManager::isAuthenticated($client)) {

            try {
                $album = $this->album_manager->findOne($id);

                if (is_null($album)) {
                    $this->action404();
                    return;
                }

                $album_json = array(
                    "name" => $album->name,
                    "description" => $album->description
                );

                echo json_encode($album_json);

            } catch (\mysqli_sql_exception $e) {
                echo "Error: ";
            }


        } elseif ($_SERVER['REQUEST_METHOD'] == "GET") {

            // Нужна ли аутентификация
            if ($client == 'admin' && !\app\components\AuthenticationManager::isAuthenticated($client)) {

                // Запись в сессию url, с кот. произошло перенаправление
                $_SESSION['relatedUrl'] = BASE_URL . $_SERVER['REQUEST_URI'];
                // Переадресация на страницу аутентификации
                header("Location: " . BASE_URL . "/login");
                return;
            }

            try {
                $album = $this->album_manager->findOne($id);

                if (is_null($album)) {
                    $this->action404();
                    return;
                }

                $images = $this->image_manager->findAllByAlbum(\intval($id));

                $this->view->render('album', array('album' => $album, 'client' => $client, 'images' => $images));

            } catch (\mysqli_sql_exception $e) {

                $error_message = null;

                if ($client == 'admin') {
                    $error_message = $e->getMessage();
                }

                $this->action500($error_message);
            }

        }
    }

    /**
     * This action will delete an album
     */
    public function actionDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            try {

                $album = $this->album_manager->findOne($_POST['albumId']);

                // Удаление лиректории для изображений
                $path = BASE_DIR . "/app/web/upload-images/" . $album->id . "_" . mb_strtolower($album->name, 'UTF-8');

                if (\is_dir($path)) {

                    $dir_handle = \opendir($path);
                    if (!$dir_handle) {
                        return;
                    }

                    while ($file = \readdir($dir_handle)) {
                        if ($file != "." && $file != "..") {
                            \unlink($path . "/" . $file);
                        }
                    }
                    \closedir($dir_handle);
                    \rmdir($path);
                }

                // Удаление альбома из БД
                $this->album_manager->delete($album->id);

            } catch (\mysqli_sql_exception $e) {

                echo "mysqli exception: ";
            }

        } else {

            $this->action404();
        }

    }

    /**
     * This action will update the album position
     */
    public function actionUpdatePosition()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            // Изменить порядок вывода на экран для альбомов
            $albums = json_decode(file_get_contents('php://input'));

            try {
                foreach ($albums as $a) {
//                $this->album_manager->updateOrderPosition($album->id, $album->position);

                    $album = $this->album_manager->findOne($a->id);
                    if (!is_null($album)) {

                        $this->album_manager->update(
                            $album,
                            null,
                            null,
                            null,
                            null,
                            $a->position
                        );
                    }

                }
            } catch (\mysqli_sql_exception $e) {
                echo "mysqli exception";
                return;
            }

        } else {
            $this->action404();
        }
    }

    /**
     * This action will edit the album
     * @param integer $id
     */
    public function actionEdit($id = null)
    {
        // Получение альбома из БД
        try {
            $album = $this->album_manager->findOne($id);

        } catch (\mysqli_sql_exception $e) {
            $this->action500($e->getMessage());
            return;
        }

        if (is_null($album)) {
            $this->action404();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST" && \app\components\AuthenticationManager::isAuthenticated("admin")) {

            // Сохранить изменения только если они есть
            if (!($album->name == $_POST['aName'] && $album->description == $_POST['aDescription'])) {

                try {

                    /*Директория. где хранятся фото альбома*/
                    $album_new_dir = null;

                    /*Если отредактировано имя - переименовать название upload - каталога*/
                    if ($album->name != $_POST['aName']) {

                        /*Замена имени альбома*/
                        $pattern = "@/" . $album->id . "_" . mb_strtolower($album->name, 'UTF-8') . "$@i";
                        $replacement = "/" . $album->id . "_" . mb_strtolower($_POST['aName'], 'UTF-8');

                        $album_new_dir = \preg_replace($pattern, $replacement, $album->dir);

                        /*Переименовать директорию*/
                        \rename($album->dir, $album_new_dir);


                        /*Изменить название директории у каждого изображения из этого альбома*/
                        $images = $this->image_manager->findAllByAlbum($album->id);

                        foreach ($images as $image) {

                            $image = $this->image_manager->findOne($image['id']);

                            /*Замена имени альбома в изображении*/
                            $pattern = "@/" . $album->id . "_" . mb_strtolower($album->name, 'UTF-8') . "/@i";
                            $replacement = "/" . $album->id . "_" . mb_strtolower($_POST['aName'], 'UTF-8') . "/";

                            $image_new_dir = \preg_replace($pattern, $replacement, $image->dir);
                            $image_new_src = \preg_replace($pattern, $replacement, $image->src);


                            /*Обновить в БД*/
                            $this->image_manager->update(
                                $image,
                                null,
                                $image_new_src,
                                $image_new_dir,
                                null,
                                null,
                                null
                            );

                        }
                    }

                    /*Обновить в БД*/
                    $this->album_manager->update($album, $_POST['aName'], null, $_POST['aDescription'], $album_new_dir, null);

                } catch (\mysqli_sql_exception $e) {
                    $this->action500($e->getMessage());
                    return;
                }
            }

            header("Location: " . BASE_URL . "/admin/albums/" . $id);


        } else if ($_SERVER['REQUEST_METHOD'] == "GET") {

            // Нужна ли аутентификация
            if (!\app\components\AuthenticationManager::isAuthenticated("admin")) {

                // Запись в сессию url, с кот. произошло перенаправление
                $_SESSION['relatedUrl'] = BASE_URL . $_SERVER['REQUEST_URI'];
                // Переадресация на страницу аутентификации
                header("Location: " . BASE_URL . "/login");
                return;
            }

            $this->view->render('edit', array('album' => $album));
        }

    }
}