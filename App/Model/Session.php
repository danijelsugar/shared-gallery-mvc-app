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

    public function cookieLoggin() {
        //cookie expiration time
        $currentTime = time();
        $currentDate = date('Y-m-d H:i:s', $currentTime);
        $cookieExpirationTime = $currentTime + (30*24*60*60);

        //set auth token directive to false
        if (!empty($_COOKIE['user_login']) && !empty($_COOKIE['random_password']) && !empty($_COOKIE['random_selector'])) {
            $isPasswordVerified = false;
            $isSelectorVerified = false;
            $isExpiryDateVerified = false;

            //Get token from id
            $userToken = new Token(Db::connect());
            $userToken = $userToken->getTokenByUserId($_COOKIE['user_login'],0);

            //validate random_password
            if (password_verify($_COOKIE['random_password'], $userToken->passwordHash)) {
                $isPasswordVerified = true;
            }

            //validate random selector
            if (password_verify($_COOKIE['random_selector'], $userToken->selectorHash)) {
                $isSelectorVerified = true;
            }

            //check cookie expiration date
            if ($userToken->expiryDate >= $currentDate) {
                $isExpiryDateVerified = true;
            }

            //check and redirect if cookie based validation returns ture else mark token as expired and clear cookies
            if (!empty($userToken->id) && $isPasswordVerified && $isSelectorVerified && $isExpiryDateVerified) {
                return true;
            } else {
                //clear cookies
                if (!empty($userToken->id)) {
                    $userToken->setTokenExpired($userToken->id);
                }
                if(isset($_COOKIE['user_login'])) {
                    setcookie('user_login', '', '-1', '/');
                }
        
                if(isset($_COOKIE['random_password'])) {
                    setcookie('random_password', '', '-1', '/');
                }
        
                if(isset($_COOKIE['random_selector'])) {
                    setcookie('random_selector', '', '-1', '/');
                }
                return false;
            }
        }
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