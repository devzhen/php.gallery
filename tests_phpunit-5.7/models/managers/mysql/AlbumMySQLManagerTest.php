<?php

namespace tests\models\managers\mysql;

define("BASE_DIR", dirname(dirname(dirname(dirname(__FILE__)))));

require dirname(BASE_DIR) . "/app/models/entities/Album.php";
require dirname(BASE_DIR) . "/app/models/managers/mysql/BaseMySQLManager.php";
require dirname(BASE_DIR) . "/app/models/managers/mysql/AlbumMySQLManager.php";

use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

class AlbumMySQLManagerTest extends \PHPUnit_Extensions_Database_TestCase
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
        return $this->createXMLDataSet(BASE_DIR . "/datasets/albums.xml");
    }


    /**
     * Тест добавления альбома
     * @covers \app\models\managers\mysql\AlbumMySQLManager::save()
     */
    public function testAddAlbum()
    {
        /*Количество строк в таблице 'album' перед тестом*/
        $row_count = $this->getConnection()->getRowCount('album');

        /*Создание альбома*/
        $album = new \app\models\entities\Album(
            null,
            "Test",
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );
        $album_manager = new \app\models\managers\mysql\AlbumMySQLManager(self::$config);
        $album_manager->save($album);


        /*Проверка, что количество строк в таблице 'album' увеличилось*/
        $this->assertEquals(
            $row_count + 1,
            $this->getConnection()->getRowCount('album'),
            "Asserting that the number of rows increased by one"
        );


        /*Текущее состояние таблицы в БД*/
        $query_table = $this->getConnection()->createQueryTable(
            "album",
            "SELECT name, date, description, dir, order_param FROM gallery.album;");

        /*Ожидаемое состояние таблицы в БД*/
        $expected_table = $this->createXMLDataSet(BASE_DIR . "/datasets/test-add-album.xml")->getTable("album");

        /*Проверка эквивалентности*/
        $this->assertTablesEqual(
            $expected_table,
            $query_table,
            "Asseerting that the tables are equal"
        );
    }


    /**
     * Тест удаления альбома
     * @covers \app\models\managers\mysql\AlbumMySQLManager::delete()
     */
    public function testDeleteAlbum()
    {
        /*Количество строк в таблице 'album' перед тестом*/
        $row_count = $this->getConnection()->getRowCount('album');

        /*Создание альбома*/
        $album = new \app\models\entities\Album(
            null,
            "Test",
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );
        $album_manager = new \app\models\managers\mysql\AlbumMySQLManager(self::$config);
        $album = $album_manager->save($album);


        /*Удаление альбома*/
        $album_manager->delete($album->id);


        /*Проверка, что количество строк в таблице 'album' не изменилось*/
        $this->assertEquals(
            $row_count,
            $this->getConnection()->getRowCount('album'),
            "Asserting that the number of rows has not changed"
        );


        /*Текущее состояние таблицы в БД*/
        $query_table = $this->getConnection()->createQueryTable(
            "album",
            "SELECT name, date, description, dir, order_param FROM gallery.album;");

        /*Ожидаемое состояние таблицы в БД*/
        $expected_table = $this->createXMLDataSet(BASE_DIR . "/datasets/test-delete-album.xml")->getTable("album");

        /*Проверка эквивалентности*/
        $this->assertTablesEqual(
            $expected_table,
            $query_table,
            "Asseerting that the tables are equal"
        );
    }
}