<?php

class LoginController
{
    /**
     * renders login page
     */
    public function index()
    {
        if (Session::getInstance()->isLoggedIn()) {
            header('Location: ' . App::config('url'));
        } else {
            $view = new View();
            $view->render('login');
        }
    }

    /**
     * authorize user and redirect to home page, if failed redirect back to login page with appropriate message
     */
    public function authorize()
    {

        if (!isset($_POST['login'])) {
            Session::getInstance()->addMessage('Something went wrong try again', 'warning');
            $this->index();
        } else {
            $user = new User(Db::connect());
            $user = $user->authenticate($_POST['email'], $_POST['password']);
            
            if (!$user) {
                Session::getInstance()->addMessage('Incorrect combination of email/password', 'warning');
                $this->index();
            } else {
                unset($user->password);
                Session::getInstance()->login($user);
                Session::getInstance()->addMessage('You have successfully logged in', 'success');

                if (isset($_POST['remember'])) {
                    // Get Current date, time
                    $currentTime = time();

                    // Set Cookie expiration for 1 month
                    $cookieExpirationTime = $currentTime + (30 * 24 * 60 * 60); // for 1 month
                    
                    setcookie('user_login', $user->id, $cookieExpirationTime, '/');

                    $randomPassword = $this->getToken(16);
                    setcookie('random_password', $randomPassword, $cookieExpirationTime, '/');

                    $randomSelector = $this->getToken(32);
                    setcookie('random_selector', $randomSelector, $cookieExpirationTime,'/');

                    $randomPasswordHash = password_hash($randomPassword, PASSWORD_DEFAULT);
                    $randomSelectorHash = password_hash($randomSelector, PASSWORD_DEFAULT);

                    $expiryDate = date('Y-m-d H:i:s', $cookieExpirationTime);
                    
                    $token = new Token(Db::connect());
                    $userToken = $token->getTokenByUserId($user->id, 0);

                    if(!empty($userToken)) {
                        $token->setTokenExpired($userToken->id);
                    }
                    $token->insertNewToken($user->id, $randomPasswordHash, $randomSelectorHash, $expiryDate);
                } else {
                     //if user login without remember me checked unset cookies
                     $this->unsetRememberMeCookies();
                }
                header('Location: ' . App::config('url'));
            }
            
        }
        
    }

    /**
     * logout user from page and redirects to home page
     */
    public function logout()
    {
        $this->unsetRememberMeCookies();
        Session::getInstance()->logout();
        Session::getInstance()->addMessage('You have successfully logged out', 'success');
        header('Location: ' . App::config('url'));
    }

    /**
     * Unset remember me cookies
     */
    protected function unsetRememberMeCookies()
    {
        if(isset($_COOKIE['user_login'])) {
            setcookie('user_login', '', '-1', '/');
        }

        if(isset($_COOKIE['random_password'])) {
            setcookie('random_password', '', '-1', '/');
        }

        if(isset($_COOKIE['random_selector'])) {
            setcookie('random_selector', '', '-1', '/');
        }
    }

    /**
     * Return random generated string
     *
     * @param int $length
     * @return string
     */
    protected function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->cryptoRandSecure(0, $max)];
        }
        return $token;
    }

    /**
     * Returns random character
     *
     * @param int $min
     * @param int $max
     * @return int
     */
    protected function cryptoRandSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
}
