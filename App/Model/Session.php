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
        if (!isset($_SESSION['flashMessage'])) {
            $_SESSION['flashMessage'] = [];
        }
        $_SESSION['flashMessage'][] = [
            'body' => $message,
            'type' => $type
        ];
    }

    public function getMessage()
    {
        if (isset($_SESSION['flashMessage'])) {
            $message = $_SESSION['flashMessage'];
            unset($_SESSION['flashMessage']);
            return $message;
            
        }
    }
}