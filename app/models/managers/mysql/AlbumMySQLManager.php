<?php

namespace app\models\managers\mysql;


class AlbumMySQLManager extends BaseMySQLManager
{

    /**
     * Method saves mysql-entity Album
     *
     * @param \app\models\entities\Album $album
     * @return \app\models\entities\Album           Returns saved in mysql object \app\models\entities\Album $album
     */
    public function save(\app\models\entities\Album $album)
    {
        try {

            $mysqli = $this->openConnection();

            $sql = "INSERT INTO " . $this->config['db'] . ".album (name, date, description) VALUES (?,?,?)";

            $stmt = $mysqli->prepare($sql);

            $stmt->bind_param('sss', $album->name, $album->date, $album->description);

            $stmt->execute();

            $stmt->close();

            $album = $this->findOne($mysqli->insert_id);

            $mysqli->close();

            return $album;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Method deletes mysql-entity Album
     *
     * @param integer $id Album ID to be deleted
     */
    public function delete($id)
    {
        try {

            $mysqli = $this->openConnection();

            $sql = "DELETE FROM " . $this->config['db'] . ".album WHERE id=?";

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
        try {

            $mysqli = $this->openConnection();

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

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Returns аrray of associative arrays.
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    public function findAll($limit = null, $offset = null)
    {
        try {

            $mysqli = $this->openConnection();

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

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Returns an album found by id or null
     * @param integer $id
     * @return \app\models\entities\Album | null
     */
    public function findOne($id)
    {
        try {

            $mysqli = $this->openConnection();

            $sql = "SELECT * FROM " . $this->config['db'] . ".album WHERE id=$id;";

            $res = $mysqli->query($sql);

            // Если данный альбом отсутствует
            if ($res->num_rows == 0) {
                return null;
            }

            $arr = $res->fetch_assoc();

            $res->close();

            $mysqli->close();

            $album = new \app\models\entities\Album(
                \intval($arr['id']),
                $arr['name'],
                \DateTime::createFromFormat('Y-m-d H:i:s', $arr['date']),
                $arr['description'],
                $arr['dir'],
                \intval($arr['order_param'])
            );

            return $album;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Returns an album found by name or null
     * @param string $name
     * @return \app\models\entities\Album | null
     */
    public function findByName($name)
    {
        try {

            $mysqli = $this->openConnection();

            $sql = "SELECT * FROM " . $this->config['db'] . ".album WHERE name='" . $name . "';";

            $res = $mysqli->query($sql);

            // Если данный альбом отсутствует
            if ($res->num_rows == 0) {
                return null;
            }

            $arr = $res->fetch_assoc();

            $res->close();

            $mysqli->close();

            $album = new \app\models\entities\Album(
                \intval($arr['id']),
                $arr['name'],
                \DateTime::createFromFormat('Y-m-d H:i:s', $arr['date']),
                $arr['description'],
                $arr['dir'],
                \intval($arr['order_param'])
            );


            return $album;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Returns count of albums
     * @return integer
     */
    public function count()
    {
        try {

            $mysqli = $this->openConnection();

            $sql = "SELECT COUNT(id) FROM " . $this->config['db'] . ".album;";

            $res = $mysqli->query($sql);

            $arr = $res->fetch_array();

            $res->close();

            $mysqli->close();

            return $arr[0];

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }
}