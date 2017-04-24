<?php

namespace app\models\managers;


class ImageMySQLManager
{
    private $config = null;

    public function __construct()
    {
        $this->config = include(BASE_DIR . '/app/config/db.php');

        $driver = new \mysqli_driver();
        $driver->report_mode = \MYSQLI_REPORT_STRICT;
    }

    public function save(\app\models\entities\Image $image)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );

        $sql = "INSERT INTO " . $this->config['db'] . ".image (name, src, dir, album_id) VALUES (?,?,?,?)";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param('sssi', $image->name, $image->src, $image->dir, $image->albumId);

        $stmt->execute();

        $image->id = $mysqli->insert_id;

        $stmt->close();

        $mysqli->close();

        return $image;
    }

    public function delete($id)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );

        $sql = "DELETE FROM " . $this->config['db'] . ".image WHERE id=?";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param('i', $id);

        $stmt->execute();

        $stmt->close();

        $mysqli->close();
    }

    public function findAll()
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        $sql = "SELECT * FROM " . $this->config['db'] . ".image ORDER BY order_param ASC;";

        $res = $mysqli->query($sql);

        $images = $res->fetch_all(\MYSQLI_ASSOC);

        $res->close();

        $mysqli->close();

        return $images;
    }

    public function findOne($id)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

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
    }

    public function findAllByAlbum($album_id)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        $sql = "SELECT * FROM " . $this->config['db'] . ".image WHERE album_id=" . $album_id . " ORDER BY order_param ASC;";

        $res = $mysqli->query($sql);

        $images = $res->fetch_all(\MYSQLI_ASSOC);

        $res->close();

        $mysqli->close();

        return $images;
    }

    public function findOneBySrc($src)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

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
    }

    public function update(\app\models\entities\Image $image, $name = null, $src = null, $dir = null, $albumId = null, $order_param = null)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );

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
    }

    /**
     * @param $album_id
     * @return array  Returns associative array.
     */
    public function findFirstByAlbum($album_id)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        $sql = "SELECT * FROM " . $this->config['db'] . ".image WHERE album_id=" . $album_id . " ORDER BY order_param LIMIT 1;";

        $res = $mysqli->query($sql);

        $image = $res->fetch_assoc();

        $res->close();

        $mysqli->close();

        return $image;
    }
}