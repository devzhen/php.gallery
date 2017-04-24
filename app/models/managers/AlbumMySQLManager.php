<?php

namespace app\models\managers;


class AlbumMySQLManager
{
    private $config = null;

    public function __construct()
    {
        $this->config = include(BASE_DIR . '/app/config/db.php');

        $driver = new \mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_STRICT;
    }

    public function save(\app\models\entities\Album $album)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );

        $sql = "INSERT INTO " . $this->config['db'] . ".album (name, date, description) VALUES (?,?,?)";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param('sss', $album->name, $album->date, $album->description);

        $stmt->execute();

        $album->id = $mysqli->insert_id;

        $stmt->close();

        $mysqli->close();

        return $album;
    }

    public function delete($id)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );

        $sql = "DELETE FROM " . $this->config['db'] . ".album WHERE id=?";

        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param('i', $id);

        $stmt->execute();

        $stmt->close();

        $mysqli->close();
    }

    /**
     * Updates album in DB
     * @param \app\models\entities\Album $album
     * @param string $name
     * @param string $date
     * @param string $description
     * @param string $dir
     * @param integer $order_param
     */
    public function update(\app\models\entities\Album $album, $name = null, $date = null, $description = null, $dir = null, $order_param = null)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );

        $sql = "UPDATE " . $this->config['db'] . ".album SET";

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

        $sql .= " WHERE id=" . $album->id . ";";

        $mysqli->query($sql);
        $mysqli->close();
    }

    /**
     * Returns аrray of associative arrays.
     * @return array
     */
    public function findAll($limit = null, $offset = null)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        $sql = "SELECT * FROM " . $this->config['db'] . ".album ORDER BY order_param ASC, date DESC";

        if (!is_null($limit)) {
            $sql .= " LIMIT $limit";
        }

        if (!is_null($offset)) {
            $sql .= " OFFSET $offset";
        }

        $sql .= ";";

        $res = $mysqli->query($sql);

        $albums = $res->fetch_all(\MYSQLI_ASSOC);

        $res->close();

        $mysqli->close();

        return $albums;
    }


    /**
     * Returns an album found by id or null
     * @param integer $id
     * @return \app\models\entities\Album | null
     */
    public function findOne($id)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        $sql = "SELECT * FROM " . $this->config['db'] . ".album WHERE id=$id;";

        $res = $mysqli->query($sql);

        // Если данный альбом отсутствует
        if ($res->num_rows == 0) {
            return null;
        }

        $arr = $res->fetch_assoc();

        $res->close();

        $mysqli->close();

        $album = new \app\models\entities\Album();
        $album->id = \intval($arr['id']);
        $album->name = $arr['name'];
        $album->date = \DateTime::createFromFormat('Y-m-d H:i:s', $arr['date']);
        $album->description = $arr['description'];
        $album->dir = $arr['dir'];

        return $album;
    }


    /**
     * Returns an album found by name or null
     * @param string $name
     * @return \app\models\entities\Album | null
     */
    public function findByName($name)
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        $sql = "SELECT * FROM " . $this->config['db'] . ".album WHERE name='" . $name . "';";

        $res = $mysqli->query($sql);

        // Если данный альбом отсутствует
        if ($res->num_rows == 0) {
            return null;
        }

        $arr = $res->fetch_assoc();

        $res->close();

        $mysqli->close();

        $album = new \app\models\entities\Album();
        $album->id = $arr['id'];
        $album->name = $arr['name'];
        $album->date = \DateTime::createFromFormat('Y-m-d H:i:s', $arr['date']);
        $album->description = $arr['description'];

        return $album;
    }

    /**
     * Returns count of albums
     * @return integer
     */
    public function count()
    {
        $mysqli = new \mysqli(
            $this->config['host'],
            $this->config['user'],
            $this->config['passw'],
            $this->config['db']
        );
        $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        $sql = "SELECT COUNT(id) FROM " . $this->config['db'] . ".album;";

        $res = $mysqli->query($sql);

        $arr = $res->fetch_array();

        $res->close();

        $mysqli->close();

        return $arr[0];
    }
}