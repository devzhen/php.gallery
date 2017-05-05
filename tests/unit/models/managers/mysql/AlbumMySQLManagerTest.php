<?php

namespace tests\models\managers\mysql;

use Codeception\Test\Unit;

class AlbumMySQLManagerTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testAddAlbum()
    {
        $this->tester->seeInDatabase('album', ['name'=>'TEST']);
    }
}