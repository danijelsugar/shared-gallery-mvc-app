<?php

namespace Gallery\Core;

use PDO;
use PDOException;

class Db extends PDO
{

    private static Db $instance;

    private function __construct(array $config)
    {

        try {
            $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['name'] . ';charset=utf8';

            parent::__construct($dsn, $config['user'], $config['password']);

            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            Session::getInstance()->addMessage('Something went wrong please try again', 'warning');
            header('Location: ' . Config::get('url'));
        }
    }

    /**
     * returns Db instance if exists if not makes new one and returns it 
     * 
     * @return Db $instance
     */
    public static function connect()
    {
        $config = Config::get('db');
        if (!isset(self::$instance)) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }
}
