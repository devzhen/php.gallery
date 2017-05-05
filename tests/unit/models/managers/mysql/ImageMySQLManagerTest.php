<?php

namespace tests\models\managers\mysql;

use Codeception\Test\Unit;

class ImageMySQLManagerTest extends Unit
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
     * @var \app\models\managers\mysql\ImageMySQLManager
     */
    private static $image_manager = null;


    /*Метод вызывается перед выполнением тестового класса(всех тестов)*/
    public static function setUpBeforeClass()
    {
        self::$db_config = include(TEST_DIR . "/unit/config/db.php");

        self::$image_manager = new \app\models\managers\mysql\ImageMySQLManager(self::$db_config);
    }


    /**
     * Тест сохранения изображения
     * @covers \app\models\managers\mysql\ImageMySQLManager::save()
     */
    public function testSaveImage()
    {
        /*Создание изображения*/
        $image = new \app\models\entities\Image(
            null,
            "TEST_IMAGE"
        );

        try {
            $image = self::$image_manager->save($image);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        $this->tester->seeInDatabase('image', ['id' => $image->id]);
    }


    /**
     * Тест удаления изображения
     * @covers \app\models\managers\mysql\ImageMySQLManager::delete()
     */
    public function testDeleteImage()
    {
        /*Создание изображения*/
        $image = new \app\models\entities\Image(
            null,
            "TEST_IMAGE"
        );
        $image = self::$image_manager->save($image);

        /*Проверка, что изображение в БД*/
        $this->tester->seeInDatabase('image', ['id' => $image->id]);

        /*Удаление изображения*/
        self::$image_manager->delete($image->id);

        /*Проверка, что изображение НЕ в БД*/
        $this->tester->dontSeeInDatabase('image', ['id' => $image->id]);
    }


    /**
     * Тест поиска всех изображений
     * @covers \app\models\managers\mysql\ImageMySQLManager::findAll()
     */
    public function testFindAllImages()
    {
        /*Поиск изображений*/
        $founded_images = self::$image_manager->findAll();

        /**
         * Получение подключения к БД
         * @var \PDO $pdo
         */
        $pdo = $this->getModule('Db')->dbh;

        $res = $pdo->query("SELECT * FROM gallery.image");

        /*Ассоциативный массив изображений из БД*/
        $arr = $res->fetchAll(\PDO::FETCH_ASSOC);

        $res = null;
        $pdo = null;

        /*Проверка*/
        $this->assertEquals($founded_images, $arr);
    }


    /**
     * Тест поиска одного изображения
     * @covers \app\models\managers\mysql\ImageMySQLManager::findOne()
     */
    public function testFindOneImage()
    {

        /*Создание изоб-ия*/
        $created_image = new \app\models\entities\Image(
            null,
            "Test"
        );
        $created_image = self::$image_manager->save($created_image);


        /*Проверка, что изображение в БД*/
        $this->tester->seeInDatabase('image', ['id' => $created_image->id]);

        /*Поиск изоб-ия*/
        $founded_image = self::$image_manager->findOne($created_image->id);

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

        /*-----------------------------------------------------------------------------------------------------------*/
        /*Поиск в албоме, у которого id=1*/
        $album_id = 1;
        $founded_images = self::$image_manager->findAllByAlbum($album_id);

        /*Проверка, что массив изображений не пуст*/
        $this->assertNotEmpty($founded_images, "Asserting that array of images is not empty");

        /**
         * Получение подключения к БД
         * @var \PDO $pdo
         */
        $pdo = $this->getModule('Db')->dbh;

        $res = $pdo->query("SELECT * FROM gallery.image WHERE album_id=$album_id ORDER BY order_param ASC;");

        /*Ассоциативный массив изображений из БД*/
        $actual_images = $res->fetchAll(\PDO::FETCH_ASSOC);

        $res = null;
        $pdo = null;

        /*Проверка эквивалентности актуального и найденного массивов*/
        $this->assertEquals($founded_images, $actual_images);


        /*-----------------------------------------------------------------------------------------------------------*/
        /*Поиск в альбоме, у которого id=1 и только первого изображения*/
        $album_id = 1;
        $limit = 1;
        $founded_images = self::$image_manager->findAllByAlbum($album_id, $limit);

        /*Проверка, что массив изображений не пуст*/
        $this->assertNotEmpty($founded_images, "Asserting that array of images is not empty");

        /*Проверка, что в массиве один элемент*/
        $this->assertEquals(1, \count($founded_images), "Asserting that count of images is one");

        /**
         * Получение подключения к БД
         * @var \PDO $pdo
         */
        $pdo = $this->getModule('Db')->dbh;

        $res = $pdo->query("SELECT * FROM gallery.image WHERE album_id=$album_id ORDER BY order_param ASC LIMIT 1;");

        /*Ассоциативный массив изображений из БД*/
        $actual_images = $res->fetchAll(\PDO::FETCH_ASSOC);

        $res = null;
        $pdo = null;

        /*Проверка эквивалентности актуального и найденного массивов*/
        $this->assertEquals($founded_images, $actual_images);


        /*-----------------------------------------------------------------------------------------------------------*/
        /*Поиск в альбоме, которого не существует*/
        $album_id = -1;

        $founded_images = self::$image_manager->findAllByAlbum($album_id);

        $this->assertEmpty($founded_images, "Asserting that array of images is empty");
    }


    /**
     * Тест поиска изображения по атрибуту src
     * @covers \app\models\managers\mysql\ImageMySQLManager::findOneBySrc()
     */
    public function testFindOneImageBySrc()
    {
        $image = self::$image_manager->findOne(1);

        $clone = self::$image_manager->findOneBySrc($image->src);

        $this->assertEquals($clone, $image);
    }


    /**
     * Тест обновления изображения
     * @covers \app\models\managers\mysql\ImageMySQLManager::update()
     */
    public function testUpdateImage()
    {
        /*Создание изображения*/
        $image = new \app\models\entities\Image(
            null,
            "Test",
            "Test",
            "Test",
            1,
            -1
        );
        $image = self::$image_manager->save($image);

        /*Проверка, что изображение в БД*/
        $this->tester->seeInDatabase('image',
            ['name' => $image->name, 'src' => $image->src, 'dir' => $image->dir]);

        /*Обновление изображения*/
        self::$image_manager->update($image, "TEMP", "TEMP", "TEMP");

        /*Проверка, что изображения со старыми значениями нет в БД*/
        $this->tester->dontSeeInDatabase('image',
            ['name' => 'Test', 'src' => 'Test', 'dir' => 'Test']);

        /*Проверка, что обновленное изображения в БД*/
        $this->tester->seeInDatabase('image',
            ['name' => 'TEMP', 'src' => 'TEMP', 'dir' => 'TEMP']);
    }


    /**
     * Для очистки БД
     */
    public function testSomething()
    {
        // Для очистки БД
    }
}