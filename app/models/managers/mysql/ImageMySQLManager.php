<?php

namespace app\models\managers\mysql;


class ImageMySQLManager extends BaseMySQLManager
{

    /**
     * Returns saved image
     * @param \app\models\entities\Image $image
     * @return \app\models\entities\Image
     */
    public function save(\app\models\entities\Image $image)
    {
        try {

            // Переделать все методы
            $mysqli = $this->openConnection();

            $sql = "INSERT INTO " . $this->config['db'] . ".image (name, src, dir, album_id) VALUES (?,?,?,?)";

            $stmt = $mysqli->prepare($sql);

            $stmt->bind_param('sssi', $image->name, $image->src, $image->dir, $image->albumId);

            $stmt->execute();

            $image->id = $mysqli->insert_id;

            $stmt->close();

            $mysqli->close();

            return $image;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Deletes image by $id
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        try {
            $mysqli = $this->openConnection();

            $sql = "DELETE FROM " . $this->config['db'] . ".image WHERE id=?";

            $stmt = $mysqli->prepare($sql);

            $stmt->bind_param('i', $id);

            $stmt->execute();

            $stmt->close();

            $mysqli->close();

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Finds all images
     * @return array of associative array
     */
    public function findAll()
    {
        try {
            $mysqli = $this->openConnection();

            $sql = "SELECT * FROM " . $this->config['db'] . ".image ORDER BY order_param ASC;";

            $res = $mysqli->query($sql);

            $images = $res->fetch_all(\MYSQLI_ASSOC);

            $res->close();

            $mysqli->close();

            return $images;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Finds one image
     * @param integer $id
     * @return \app\models\entities\Image | null
     */
    public function findOne($id)
    {
        try {
            $mysqli = $this->openConnection();

            $sql = "SELECT * FROM " . $this->config['db'] . ".image WHERE id=$id;";

            $res = $mysqli->query($sql);

            // Если данное изображение отсутствует
            if ($res->num_rows == 0) {
                return null;
            }

            $arr = $res->fetch_assoc();

            $res->close();

            $mysqli->close();

            $image = new \app\models\entities\Image();
            $image->id = $arr['id'];
            $image->name = $arr['name'];
            $image->src = $arr['src'];
            $image->dir = $arr['dir'];
            $image->description = $arr['description'];
            $image->albumId = $arr['album_id'];
            $image->order_param = $arr['order_param'];

            return $image;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Finds all images by album $id
     * @param integer $album_id
     * @param integer $limit
     * @return array of associative array | empty array
     */
    public function findAllByAlbum($album_id, $limit = null)
    {
        try {
            $mysqli = $this->openConnection();

            $sql = "SELECT * FROM " . $this->config['db'] . ".image WHERE album_id=" . $album_id . " ORDER BY order_param ASC";
            if (!is_null($limit)) {
                $sql .= " LIMIT $limit";
            }
            $sql .= ";";

            $res = $mysqli->query($sql);

            if ($res->num_rows == 0) {
                return array();
            }

            $images = $res->fetch_all(\MYSQLI_ASSOC);

            $res->close();

            $mysqli->close();

            return $images;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Finds one image by $src
     * @param string $src
     * @return \app\models\entities\Image|null
     */
    public function findOneBySrc($src)
    {
        try {
            $mysqli = $this->openConnection();

            $sql = "SELECT * FROM " . $this->config['db'] . ".image WHERE src='" . $src . "';";

            $res = $mysqli->query($sql);

            // Если данное изображение отсутствует
            if ($res->num_rows == 0) {
                return null;
            }

            $arr = $res->fetch_assoc();

            $res->close();

            $mysqli->close();

            $image = new \app\models\entities\Image();
            $image->id = $arr['id'];
            $image->name = $arr['name'];
            $image->src = $arr['src'];
            $image->dir = $arr['dir'];
            $image->albumId = $arr['album_id'];
            $image->order_param = $arr['order_param'];

            return $image;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Updates image
     * @param \app\models\entities\Image $image
     * @param string $name
     * @param string $src
     * @param string $dir
     * @param integer $albumId
     * @param integer $order_param
     * @return void
     */
    public function update(\app\models\entities\Image $image, $name = null, $src = null, $dir = null, $albumId = null, $order_param = null)
    {
        try {
            $mysqli = $this->openConnection();

            $sql = "UPDATE " . $this->config['db'] . ".image SET";

            /*Необходима ли запятая в sql запросе?*/
            $is_comma_necessary = false;

            /*Необходимы ли кавычки в sql запросе?*/
            $quotes = false;

            /*Анализ параметров*/
            $rm = new \ReflectionMethod($this, 'update');
            $params = $rm->getParameters();

            for ($i = 1; $i < func_num_args(); $i++) {

                /*Если параметр !=null*/
                if (!is_null(func_get_arg($i))) {

                    /*Нужно ли добавлять ',' в sql запрос*/
                    if ($is_comma_necessary) {
                        $sql .= ",";
                    }

                    $sql .= " {$params[$i]->name}=";

                    /*Нужно ли добавлять кавычки для строкового параметра*/
                    if (is_string(func_get_arg($i))) {
                        $sql .= "'";
                        $quotes = true;
                    }

                    $sql .= func_get_arg($i);

                    /*Были ли открыты кавычки*/
                    if ($quotes) {
                        $sql .= "'";
                        $quotes = false;
                    }

                    $is_comma_necessary = true;
                }
            }

            $sql .= " WHERE id=" . $image->id . ";";

            $mysqli->query($sql);
            $mysqli->close();


        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }
}