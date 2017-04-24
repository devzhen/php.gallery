<?php

namespace app\components;


class PaginationManager
{
    /** @var integer. Общее кол-во элементов */
    private $countOfElements = null;

    /** @var integer. Кол-во элементов на одну страницу */
    private $limit = null;

    /** @var integer. Кол-во страниц(вычисленное) */
    private $countOfPages = null;

    /** @var array. Служебный массив */
    private $arrayPages = null;

    /** @var integer. Кол-во кнопок постраничной навигации */
    private $maxCountOfPaginationButtons = null;

    /** @var array Кнопки постраничной навигации */
    private $htmlPaginationButtons = null;


    /**
     * Pagination constructor.
     * @param integer $countOfElements - Общее кол-во элементов
     * @param integer $limit - Кол-во элементов на одну страницу
     * @param integer $maxCountOfPaginationButtons - Кол-во кнопок постраничной навигации
     */
    public function __construct($countOfElements, $limit, $maxCountOfPaginationButtons)
    {
        $this->countOfElements = $countOfElements;
        $this->limit = $limit;
        $this->maxCountOfPaginationButtons = $maxCountOfPaginationButtons;

        /*
        Определение количества страниц.
        Фун-ия ceil - округляет дробь в большую сторону
        */
        $this->countOfPages = ceil($this->countOfElements / $this->limit);

        /*Создание массива (страница=>смещение) и создание массива кнопок постраничной навигации*/
        $this->createPagesArray();
    }

    /**
     * Метод возвращает вычисленный параметр OFFSET для sql-запроса SELECT ... LIMIT .. OFFSET
     * в зависимости от номера страницы. Или null если номер страницы некорректный.
     *
     * @param integer $page_number Номер страницы
     * @return null | integer
     */
    public function getOffset($page_number)
    {
        if ($page_number > \count($this->arrayPages) || $page_number < 1) {
            return null;
        }

        return $this->arrayPages[$page_number];
    }


    /**
     * Метод возвращает html-строку кнопок постраничной навигации
     * @param integer $page_number Номер страницы
     * @return string HTML Bootstrap pagination buttons
     */
    public function getPaginationButtons($page_number)
    {

        if ($this->countOfPages == 1) {
            return null;
        }

        /*Определение первой pagination кнопки*/
        $offset = $page_number - \floor($this->maxCountOfPaginationButtons / 2) - 1;
        if ($offset < 0) {
            $offset = 0;
        }

        /*Определение последней pagination кнопки*/
        if ($offset + $this->maxCountOfPaginationButtons >= count($this->htmlPaginationButtons)) {
            $offset = 0 - $this->maxCountOfPaginationButtons;
        }

        /*Формирование массива pagination кнопок.
        Здесь из общего массива с pagination кнопками вырезается часть, в зависимости от номера
        страницы $page_number, кол-во этой части массива указывается в $this->maxCountOfPaginationButtons*/
        $buttons = \array_slice($this->htmlPaginationButtons, $offset, $this->maxCountOfPaginationButtons, true);


        /*Убрать ссылки в текущей активной кнопке*/
        if (\array_key_exists($page_number, $buttons)) {

            $btn = $buttons[$page_number];

            $btn = \preg_replace('@<li>@', '<li class="active">', $btn);
            $btn = \preg_replace('@<a href=".+">@', '<span>', $btn);
            $btn = \preg_replace('@</a>@', '</span>', $btn);

            $buttons[$page_number] = $btn;
        }

        reset($buttons);

        /*Нужна ли кнопка 'В начало'*/
        $firstKey = key($buttons);

        if ($firstKey > ceil($this->maxCountOfPaginationButtons / 2)) {

            $btn = '<li><span class="space">...</span></li>';
            /*Добавить в начало массива pagination кнопок*/
            \array_unshift($buttons, $btn);

            $btn = '<li><a href="' . BASE_URL . '/page/';
            $btn .= 1;
            $btn .= '">';
            $btn .= 1;
            $btn .= '</a></li>';

            /*Добавить в начало массива pagination кнопок*/
            \array_unshift($buttons, $btn);
        }

        /*Нужна ли кнопка 'В конец'*/
        $lastKey = $firstKey + $this->maxCountOfPaginationButtons - 1;

        if ($lastKey < $this->countOfPages - floor($this->maxCountOfPaginationButtons / 2)) {

            $btn = '<li><span class="space">...</span></li>';
            /*Добавить в конец массива pagination кнопок*/
            \array_push($buttons, $btn);

            $btn = '<li><a href="' . BASE_URL . '/page/';
            $btn .= $this->countOfPages;
            $btn .= '">';
            $btn .= $this->countOfPages;
            $btn .= '</a></li>';

            /*Добавить в конец массива pagination кнопок*/
            \array_push($buttons, $btn);
        }

        /*Добавить в начало массива pagination кнопок*/
        \array_unshift($buttons, '<ul class="pagination">');

        /*Добавить в конец массива pagination кнопок*/
        \array_push($buttons, " </ul > ");

        return \implode($buttons);
    }

    /**
     * Заполнение массива $this->arrayPages, типа:
     * array(){
     *      1 => 7,
     *      2 => 14
     * }
     * Ключ     - номер страницы.
     * Значение - параметр OFFSET для sql-запроса SELECT ... LIMIT .. OFFSET
     *
     *
     * Заполнение массива $this->htmlPaginationButtons, кот. содержит html - код
     * кнопок постраничной навигации
     * array(){
     *      1 => '<li><a href = ".../page / 1">1</a></li>',
     *      2 => '<li><a href = ".../page / 2">2</a></li>'
     * }
     * @return void
     */
    private function createPagesArray()
    {

        for ($i = 0; $i < $this->countOfPages; $i++) {

            /*Заполнение массива - страница=>смещение*/
            $this->arrayPages[$i + 1] = $i * $this->limit;


            /*Заполнение массива - html-кнопки постраничной навигации*/
            $button = '<li><a href="' . BASE_URL . '/page/';
            $button .= $i + 1;
            $button .= '">';
            $button .= $i + 1;
            $button .= '</a></li>';


            $this->htmlPaginationButtons[$i + 1] = $button;
        }
    }
}