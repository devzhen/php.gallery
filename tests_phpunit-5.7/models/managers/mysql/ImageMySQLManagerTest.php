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


    /**
     * Тест сохранения изображения
     * @covers \app\models\managers\mysql\ImageMySQLManager::save()
     */
    public function testSaveImage()
    {
        /*Создание изображения*/
        $image_manager = new \app\models\managers\mysql\ImageMySQLManager(self::$config);

        $image = new \app\models\entities\Image(
            null,
            "TEST_IMAGE"
        );
        $image_manager->save($image);

        /*Таблицы изображений в БД*/
        $actual_images = $this->getConnection()->createQueryTable(
            'image',
            "SELECT * FROM gallery.image;"
        );

        /*Ожидаемая таблица изображений*/
        $expected_images = $this->createXMLDataSet(BASE_DIR . "/datasets/test-add-image.xml")->getTable('image');


        $this->assertTablesEqual($expected_images, $actual_images);
    }


    /**
     * Тест удаления изображения
     * @covers \app\models\managers\mysql\ImageMySQLManager::delete()
     */
    public function testDeleteImage()
    {
        /*Создание изображения*/
        $image_manager = new \app\models\managers\mysql\ImageMySQLManager(self::$config);

        $image = new \app\models\entities\Image(
            null,
            "TEST_IMAGE"
        );
        $image = $image_manager->save($image);

        /*Удаление изображения*/
        $image_manager->delete($image->id);

        /*Актуальная таблица изображений из БД*/
        $actual_image_table = $this->getConnection()->createQueryTable(
            'image',
            "SELECT * FROM gallery.image;"
        );

        /*Ожидаемая таблица изображений*/
        $expected_image_table = $this->createXMLDataSet(BASE_DIR . "/datasets/gallery.xml")->getTable('image');

        $this->assertTablesEqual($expected_image_table, $actual_image_table);
    }


    /**
     * Тест поиска всех изображений
     * @covers \app\models\managers\mysql\ImageMySQLManager::findAll()
     */
    public function testFindAllImages()
    {
        $image_manager = new \app\models\managers\mysql\ImageMySQLManager(self::$config);

        /*Поиск изображений*/
        $founded_images = $image_manager->findAll();

        /*Текущее состояние таблицы изображений в БД*/
        $images_table = $this->getConnection()->createDataSet(['image'])->getTable('image');
        $actual_images = array();

        /*Создание массива актуальных из БД альбомов*/
        for ($i = 0; $i < $images_table->getRowCount(); $i++) {
            $actual_images[] = $images_table->getRow($i);
        }

        /*Проверка*/
        $this->assertEquals(
            $founded_images,
            $actual_images,
            "Assertion that arrays of images are equal");
    }


    /**
     * Тест поиска одного изображения
     * @covers \app\models\managers\mysql\ImageMySQLManager::findOne()
     */
    public function testFindOneImage()
    {
        $image_manager = new \app\models\managers\mysql\ImageMySQLManager(self::$config);

        /*Создание изоб-ия*/
        $created_image = new \app\models\entities\Image(
            null,
            "Test"
        );
        $created_image = $image_manager->save($created_image);


        /*Поиск изоб-ия*/
        $founded_image = $image_manager->findOne($created_image->id);

        /*Проверка эквивалентности изоб-ий*/
        $this->assertEquals(
            $created_image,
            $founded_image,
            "Assertion that images are equal");
    }


    /**
     * Тест поиска изображений в конкретном альбоме
     * @covers \app\models\managers\mysql\ImageMySQLManager::findAllByAlbum()
     */
    public function testFindAllImagesByAlbum()
    {
        $image_manager = new \app\models\managers\mysql\ImageMySQLManager(self::$config);

        /*-----------------------------------------------------------------------------------------------------------*/
        /*Поиск в албоме, у которого id=1*/
        $album_id = 1;
        $founded_images = $image_manager->findAllByAlbum($album_id);

        /*Проверка, что массив изображений не пуст*/
        $this->assertNotEmpty($founded_images, "Asserting that array of images is not empty");

        /*Получение актуальной таблицы изображений альбома с id=1*/
        $table_images = $this->getConnection()->createQueryTable(
            'images',
            "SELECT * FROM gallery.image WHERE album_id=" . $album_id . " ORDER BY order_param ASC");
        $actual_images = array();

        /*Создание ассоциативного массива изображений*/
        for ($i = 0; $i < $table_images->getRowCount(); $i++) {
            $actual_images[] = $table_images->getRow($i);
        }

        /*Проверка эквивалентности актуального и найденного массивов*/
        $this->assertEquals($founded_images, $actual_images);


        /*-----------------------------------------------------------------------------------------------------------*/
        /*Поиск в альбоме, у которого id=1 и только первого изображения*/
        $album_id = 1;
        $limit = 1;
        $founded_images = $image_manager->findAllByAlbum($album_id, $limit);

        /*Проверка, что массив изображений не пуст*/
        $this->assertNotEmpty($founded_images, "Asserting that array of images is not empty");

        /*Проверка, что в массиве один элемент*/
        $this->assertEquals(1, \count($founded_images), "Asserting that count of images is one");

        /*Получение актуальной таблицы с одним изображением у альбома с id=1*/
        $table_images = $this->getConnection()->createQueryTable(
            'images',
            "SELECT * FROM gallery.image WHERE album_id=" . $album_id . " ORDER BY order_param ASC LIMIT " . $limit . ";");
        $actual_images = array();

        /*Создание ассоциативного массива изображений*/
        for ($i = 0; $i < $table_images->getRowCount(); $i++) {
            $actual_images[] = $table_images->getRow($i);
        }

        /*Проверка эквивалентности актуального и найденного массивов*/
        $this->assertEquals($founded_images, $actual_images);


        /*-----------------------------------------------------------------------------------------------------------*/
        /*Поиск в альбоме, которого не существует*/
        $album_id = -1;

        $founded_images = $image_manager->findAllByAlbum($album_id);

        $this->assertEmpty($founded_images, "Asserting that array of images is empty");
    }


    /**
     * Тест поиска изображения по атрибуту src
     * @covers \app\models\managers\mysql\ImageMySQLManager::findOneBySrc()
     */
    public function testFindOneImageBySrc()
    {
        $image_manager = new \app\models\managers\mysql\ImageMySQLManager(self::$config);

        $image = $image_manager->findOne(1);

        $clone = $image_manager->findOneBySrc($image->src);

        $this->assertEquals($clone, $image);
    }


    /**
     * Тест обновления изображения
     * @covers \app\models\managers\mysql\ImageMySQLManager::update()
     */
    public function testUpdateImage()
    {
        /*Создание изображения*/
        $image_manager = new \app\models\managers\mysql\ImageMySQLManager(self::$config);

        $image = new \app\models\entities\Image(
            null,
            "Test",
            "Test",
            "Test",
            1,
            -1
        );
        $image = $image_manager->save($image);

        /*Обновление изображения*/
        $image_manager->update($image, "TEMP", "TEMP", "TEMP");

        /*Обновленное изоб-ие в БД*/
        $actual_image_table = $this->getConnection()->createQueryTable('image',
            "SELECT name, album_id, src, dir, order_param FROM gallery.image WHERE id=" . $image->id);

        /*Ожидаемое изображение*/
        $expected_image_table = $this->createXMLDataSet(BASE_DIR . "/datasets/test-update-image.xml")->getTable('image');

        $this->assertTablesEqual($expected_image_table, $actual_image_table);
    }


    /**
     * Для очистки БД
     */
    public function testSomething()
    {
        // Для очистки БД
    }
}