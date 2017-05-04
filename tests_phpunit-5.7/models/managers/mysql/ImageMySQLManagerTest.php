<?php

namespace tests\models\managers\mysql;

define("BASE_DIR", dirname(dirname(dirname(dirname(__FILE__)))));

require_once dirname(BASE_DIR) . "/app/models/entities/Image.php";
require_once dirname(BASE_DIR) . "/app/models/managers/mysql/BaseMySQLManager.php";
require_once dirname(BASE_DIR) . "/app/models/managers/mysql/ImageMySQLManager.php";

use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

class ImageMySQLManagerTest extends \PHPUnit_Extensions_Database_TestCase
{

    private static $config = null;


    public static function setUpBeforeClass()
    {
        $path = BASE_DIR . "/config/db.php";
        self::$config = include($path);
    }

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        try {

            $pdo = new \PDO(
                self::$config['dsn'],
                self::$config['user'],
                self::$config['passw']
            );

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $this->createDefaultDBConnection($pdo);

        } catch (\PDOException $exception) {
            $this->markTestSkipped("No database connection");
        }
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return $this->createXMLDataSet(BASE_DIR . "/datasets/gallery.xml");
    }
}