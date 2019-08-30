<?php

class Db extends PDO 
{

    private static $instance;

    private function __construct($config)
    {
        $dsn = 'mysql:host='.$config['host'].';dbname='.$config['name'].';charset=utf8';

        parent::__construct($dsn, $config['user'], $config['password']);

        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * returns Db instance if exists if not makes new one and returns it 
     * 
     * @return $instance
     */
    public static function connect()
    {
        $config = App::config('db');
        if (!isset(self::$instance)) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }

}