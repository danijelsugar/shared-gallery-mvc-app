<?php

class Session 
{
    private static $instance;
    private function __construct()
    {
        session_start();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addMessage($message, $type = 'success')
    {
        
    }
}