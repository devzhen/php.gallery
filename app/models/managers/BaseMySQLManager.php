<?php

namespace app\models\managers;


abstract class BaseMySQLManager
{
    /**
     * @var array Database configuration file
     */
    protected $config = null;

    /**
     * @var \mysqli MySQL connection
     */
    protected $mysqli = null;

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
        /*Указание драйверу mysql использовать exceptions вместо errors*/
        $driver = new \mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

        /*Подключение к БД*/
        try {

            $this->mysqli = new \mysqli(
                $this->config['host'],
                $this->config['user'],
                $this->config['passw'],
                $this->config['db']
            );

            /*Указание преобразовывать результаты из БД в PHP типы*/
            $this->mysqli->options(\MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

        } catch (\mysqli_sql_exception $e) {
            throw $e;
        }
    }


    /**
     * Closes mysqli connection
     */
    protected function closeConnection()
    {
        if (!is_null($this->mysqli->host_info)) {
            $this->mysqli->close();
        }
    }
}