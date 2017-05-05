<?php
// This is global bootstrap for autoloading
define("ROOT_DIR", dirname(__DIR__));
define("TEST_DIR", __DIR__);

require_once ROOT_DIR . "/app/models/entities/Album.php";
require_once ROOT_DIR . "/app/models/entities/Image.php";
require_once ROOT_DIR . "/app/models/managers/mysql/BaseMySQLManager.php";
require_once ROOT_DIR . "/app/models/managers/mysql/AlbumMySQLManager.php";
require_once ROOT_DIR . "/app/models/managers/mysql/ImageMySQLManager.php";