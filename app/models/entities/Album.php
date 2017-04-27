<?php

namespace app\models\entities;

class Album
{
    public $id = null;
    public $name = null;
    public $date = null;
    public $description = null;
    public $dir = null;
    public $order_param = null;
    public $images = null;

    /**
     * Album constructor.
     * @param integer $id
     * @param string $name
     * @param \DateTime $date
     * @param string $description
     * @param string $dir
     * @param integer $order_param
     */
    public function __construct($id = null, $name = null, $date = null, $description = null, $dir = null, $order_param = null, $images = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->date = $date;
        $this->description = $description;
        $this->dir = $dir;
        $this->order_param = $order_param;
    }


}