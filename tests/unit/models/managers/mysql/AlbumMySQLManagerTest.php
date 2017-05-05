<?php

namespace tests\models\managers\mysql;

use Codeception\Test\Unit;

class AlbumMySQLManagerTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var array Database configuration
     */
    private static $db_config = null;

    /**
     * @var \app\models\managers\mysql\AlbumMySQLManager
     */
    private static $album_manager = null;


    /*Метод вызывается перед выполнением тестового класса(всех тестов)*/
    public static function setUpBeforeClass()
    {
        self::$db_config = include(TEST_DIR . "/unit/config/db.php");

        self::$album_manager = new \app\models\managers\mysql\AlbumMySQLManager(self::$db_config);
    }


    /**
     * Тест добавления альбома
     * @covers \app\models\managers\mysql\AlbumMySQLManager::save()
     */
    public function testAddAlbum()
    {

        $num_rows = $this->tester->grabNumRecords('album');

        /*Создание альбома*/
        $album = new \app\models\entities\Album(
            null,
            "Test",
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );

        self::$album_manager->save($album);

        /*Проверка, что альбом в БД*/
        $this->tester->seeInDatabase('album', ['name' => 'Test', 'description' => 'Test description']);


        $this->assertEquals($num_rows + 1, $this->tester->grabNumRecords('album'));
    }


    /**
     * Тест удаления альбома
     * @covers \app\models\managers\mysql\AlbumMySQLManager::delete()
     */
    public function testDeleteAlbum()
    {
        $num_rows = $this->tester->grabNumRecords('album');

        /*Создание альбома*/
        $album = new \app\models\entities\Album(
            null,
            "Test",
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );

        $album = self::$album_manager->save($album);

        /*Удаление альбома*/
        self::$album_manager->delete($album->id);

        /*Проверка, что альбома нет в БД*/
        $this->tester->dontSeeInDatabase('album', ['id' => $album->id]);

        $this->assertEquals($num_rows, $this->tester->grabNumRecords('album'));
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
        $album = self::$album_manager->save($album);

        $this->tester->wantTo("I want to check, that album exists");

        /*Проверка создания*/
        $this->tester->seeInDatabase(
            'album',
            ['name' => 'Test', 'date' => '2000-01-01 00:00:00', 'description' => 'Test description']
        );

        /*Обновление альбома*/
        self::$album_manager->update(
            $album,
            "TEMP",
            "2000-01-01 00:00:00",
            "TEMP DESCRIPTION",
            "",
            -1
        );

        $this->tester->wantTo("I want to check, that album was updated");

        /*Проверка обновления*/
        $this->tester->seeInDatabase(
            'album',
            ['name' => 'TEMP', 'date' => '2000-01-01 00:00:00', 'description' => 'TEMP DESCRIPTION']
        );
    }


    /**
     * Тест поиска всех альбомов
     * @covers \app\models\managers\mysql\AlbumMySQLManager::findAll()
     */
    public function testFindAllAlbums()
    {
        /*Поиск альбомов*/
        $founded_albums = self::$album_manager->findAll();

        /**
         * Получение подключения к БД
         * @var \PDO $pdo
         */
        $pdo = $this->getModule('Db')->dbh;

        $res = $pdo->query("SELECT * FROM gallery.album ORDER BY order_param ASC, date DESC");

        /*Ассоциативный массив альбомов из БД*/
        $arr = $res->fetchAll(\PDO::FETCH_ASSOC);

        $res = null;
        $pdo = null;

        /*Проверка утверждения, что массивы идентичны*/
        $this->assertEquals($founded_albums, $arr);
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
        $created_album = self::$album_manager->save($created_album);

        /*Проверка, что альбом в БД*/
        $this->tester->seeInDatabase('album', ['id' => $created_album->id]);

        /*Поиск альбома*/
        $founded_album = self::$album_manager->findOne($created_album->id);

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
        $unique_name = md5(md5(uniqid("", true)));

        /*Создание альбома*/
        $created_album = new \app\models\entities\Album(
            null,
            $unique_name,
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );

        self::$album_manager->save($created_album);

        /*Поиск по имени в БД вновь созданного альбома*/
        $this->tester->seeInDatabase('album', ['name' => $unique_name]);

    }


    /**
     * Тест количества альбомов
     * @covers \app\models\managers\mysql\AlbumMySQLManager::count()
     */
    public function testCount()
    {
        /*Совпадает ли кол-во записей в БД*/
        $this->assertEquals(
            self::$album_manager->count(),
            $this->tester->grabNumRecords('album')
        );

        /*Создание альбома*/
        $album = new \app\models\entities\Album(
            null,
            "Test",
            "2000-01-01 00:00:00",
            "Test description",
            ""
        );
        $album = self::$album_manager->save($album);

        /*Совпадает ли кол-во записей в БД*/
        $this->assertEquals(
            self::$album_manager->count(),
            $this->tester->grabNumRecords('album')
        );

        /*Удаление альбома*/
        self::$album_manager->delete($album->id);

        /*Совпадает ли кол-во записей в БД*/
        $this->assertEquals(
            self::$album_manager->count(),
            $this->tester->grabNumRecords('album')
        );
    }
}