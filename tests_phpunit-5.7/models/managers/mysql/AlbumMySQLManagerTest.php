<?php

namespace tests\models\managers\mysql;

define("BASE_DIR", dirname(dirname(dirname(dirname(__FILE__)))));

require_once dirname(BASE_DIR) . "/app/models/entities/Album.php";
require_once dirname(BASE_DIR) . "/app/models/managers/mysql/BaseMySQLManager.php";
require_once dirname(BASE_DIR) . "/app/models/managers/mysql/AlbumMySQLManager.php";

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
        return $this->createXMLDataSet(BASE_DIR . "/datasets/gallery.xml");
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


    /**
     * Тест обновления альбома
     * @covers \app\models\managers\mysql\AlbumMySQLManager::update()
     */
    public function testUpdateAlbum()
    {
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

        /*Обновление альбома*/
        $album_manager->update(
            $album,
            "TEST",
            "2000-01-01 00:00:00",
            "TEST DESCRIPTION",
            "",
            -1
        );


        /*Текущее состояние*/
        $query_table = $this->getConnection()->createQueryTable(
            'album',
            "SELECT name, date, description, dir, order_param FROM gallery.album WHERE id=$album->id;"
        );

        /*Ожидаемый результат*/
        $expected_table = $this->createXMLDataSet(BASE_DIR . "/datasets/test-update-album.xml")->getTable('album');


        /*Проверка соответствия таблиц*/
        $this->assertTablesEqual($expected_table, $query_table);
    }


    /**
     * Тест поиска всех альбомов
     * @covers \app\models\managers\mysql\AlbumMySQLManager::findAll()
     */
    public function testFindAllAlbums()
    {
        $album_manager = new \app\models\managers\mysql\AlbumMySQLManager(self::$config);

        /*Поиск альбомов*/
        $founded_albums = $album_manager->findAll();

        /*Текущее состояние таблицы альбомов в БД*/
        $albums_table = $this->getConnection()->createDataSet(['album'])->getTable('album');
        $actual_albums = array();

        /*Создание массива актуальных из БД альбомов*/
        for ($i = $albums_table->getRowCount() - 1; $i >= 0; $i--) {
            $actual_albums[] = $albums_table->getRow($i);
        }

        /*Проверка*/
        $this->assertEquals(
            $founded_albums,
            $actual_albums,
            "Assertion that arrays of albums are equal");
    }


    /**
     * Тест поиска одного альбома
     * @covers \app\models\managers\mysql\AlbumMySQLManager::findOne()
     */
    public function testFindOneAlbum()
    {
        /*Создание альбома*/
        $created_album = new \app\models\entities\Album(
            null,
            "Test",
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );
        $album_manager = new \app\models\managers\mysql\AlbumMySQLManager(self::$config);
        $created_album = $album_manager->save($created_album);


        /*Поиск альбома*/
        $founded_album = $album_manager->findOne($created_album->id);

        /*Проверка эквивалентности альбомов*/
        $this->assertEquals($created_album,
            $founded_album,
            "Assertion that albums are equal");
    }


    /**
     * Тест поиска альбома по имени
     * @covers \app\models\managers\mysql\AlbumMySQLManager::findByName()
     */
    public function testFindAlbumByName()
    {
        $name = md5(md5(uniqid("", true)));

        $album_manger = new \app\models\managers\mysql\AlbumMySQLManager(self::$config);

        /*Создание альбома*/
        $created_album = new \app\models\entities\Album(
            null,
            $name,
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );
        $created_album = $album_manger->save($created_album);


        /*Поиск альбома по имени*/
        $founded_album = $album_manger->findByName($name);

        /*Проверка*/
        $this->assertEquals($founded_album->id, $created_album->id);
        $this->assertEquals($founded_album->name, $created_album->name);
        $this->assertEquals($founded_album->date, $created_album->date);
        $this->assertEquals($founded_album->description, $created_album->description);

    }


    /**
     * Тест количества альбомов
     * @covers \app\models\managers\mysql\AlbumMySQLManager::count()
     */
    public function testCount()
    {
        $album_manager = new \app\models\managers\mysql\AlbumMySQLManager(self::$config);

        $this->assertEquals(
            $album_manager->count(),
            $this->getConnection()->getRowCount('album'));

        /*Создание альбома*/
        $album = new \app\models\entities\Album(
            null,
            "Test",
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );

        $album = $album_manager->save($album);

        $this->assertEquals(
            $album_manager->count(),
            $this->getConnection()->getRowCount('album'));

        $album_manager->delete($album->id);

        $this->assertEquals(
            $album_manager->count(),
            $this->getConnection()->getRowCount('album'));
    }
}