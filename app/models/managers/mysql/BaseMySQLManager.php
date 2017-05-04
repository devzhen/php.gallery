<?php

namespace app\models\managers\mysql;


abstract class BaseMySQLManager
{
    /**
     * @var array Database configuration file
     */
    protected $config = null;

    /**
     * Constructor of base class for all mysql managers classes
     * @param array $config Database configuration file
     */
    public function __construct($config)
    {
        $this->config = $config;
    }


    /**
     * Creates mysqli connection
     */
    protected function openConnection()
    {
        /*Подключение к БД*/
        try {

            $mysqli = new \mysqli(
                $this->config['host'],
                $this->config['user'],
                $this->config['passw'],
                $this->config['db']
            );

            /*Указание драйверу mysql использовать exceptions вместо errors*/
            $driver = new \mysqli_driver();
            $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

            /*Указание преобразовывать результаты из БД в PHP типы*/
            $mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

            return $mysqli;

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }
}