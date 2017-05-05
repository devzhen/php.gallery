<?php

namespace app\models\entities;


class Image
{
    public $id = null;
    public $name = null;
    public $src = null;
    public $dir = null;
    public $albumId = null;
    public $order_param = null;

    /**
     * Image constructor.
     * @param integer $id
     * @param string $name
     * @param string $src
     * @param string $dir
     * @param integer $albumId
     * @param integer $order_param
     */
    public function __construct($id = null, $name = null, $src = "#NULL#", $dir = "#NULL#", $albumId = 1, $order_param = -1)
    {
        $this->id = $id;
        $this->name = $name;
        $this->src = $src;
        $this->dir = $dir;
        $this->albumId = $albumId;
        $this->order_param = $order_param;
    }


}