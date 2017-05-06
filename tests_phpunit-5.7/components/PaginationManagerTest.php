<?php

namespace tests\components;

require dirname(dirname(__DIR__)) . "/app/components/PaginationManager.php";

use PHPUnit\Framework\TestCase;

class PaginationManagerTest extends TestCase
{
    /**
     * @dataProvider paginationParameters
     * @covers       \app\components\PaginationManager::getSQLOffset()
     *
     * @param int $countOfAllElements - Общее кол-во элементов
     * @param int $countOfElementsOnPage - Кол-во элементов на странице
     * @param int $maxCountPaginationButtons - Кол-во кнопок пагинации
     * @param int $page - Номер текущей страницы
     * @param int|null $sqlOffset - Вычисленное значение OFFSET для SQL запроса
     */
    public function testGetSQLOffset($countOfAllElements, $countOfElementsOnPage, $maxCountPaginationButtons, $page, $sqlOffset)
    {
        $pm = new \app\components\PaginationManager($countOfAllElements, $countOfElementsOnPage, $maxCountPaginationButtons);

        $this->assertEquals($sqlOffset, $pm->getSQLOffset($page));
    }

    public function paginationParameters()
    {
        return [
            [1, 1, 1, 1, 0],
            [2, 2, 2, 2, null],
            [13, 12, 5, 1, 0],
            [13, 12, 5, 2, 12],
        ];
    }
}