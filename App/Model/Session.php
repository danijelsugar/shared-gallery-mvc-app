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

    public function login($user)
    {
        $_SESSION['is_logged_in'] = $user;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['is_logged_in']) ? true : false;
    }

    public function logout()
    {
        unset($_SESSION['is_logged_in']);
    }

    /**
     * @param $message
     * @param $type;
     */
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

    /**
     * @return array
     */
    public function getMessage()
    {
        if (isset($_SESSION['flashMessage'])) {
            $message = $_SESSION['flashMessage'];
            unset($_SESSION['flashMessage']);
            return $message;
            
        }
    }
}