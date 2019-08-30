<?php

class Session 
{
    private static $instance;
    private function __construct()
    {
        session_start();
    }

    /**returns session instance if exists if not creates new one and returns it
     * 
     * @return $instance
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * store authorized user data in session so it can be accessed throughout the app
     */
    public function login($user)
    {
        $_SESSION['is_logged_in'] = $user;
    }

    /**
     * if user is logged in returns true, if not returns false
     * 
     * @return boolean
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['is_logged_in']) ? true : false;
    }

    /**
     * unset user data from session and logging him of
     */
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
     * returns array of messages and unsets them after they are displayed, 
     * so they are displayed once and when user refreshes page they wont display again
     * 
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