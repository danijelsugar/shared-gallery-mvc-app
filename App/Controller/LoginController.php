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
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $valid = true;

        if ($email === '') {
            $valid = false;
            Session::getInstance()->addMessage('Email required', 'warning');
        }

        if ($password === '') {
            $valid = false;
            Session::getInstance()->addMessage('Password required', 'warning');
        }

        if ($valid) {
            $db = Db::connect();
            $stmt = $db->prepare('select * from user where email=:email');
            $stmt->bindValue('email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch();

                if (password_verify($password, $user->password)) {
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
                        
                        $userToken = Token::getTokenByUserId($user->id, 0);
                        if(!empty($userToken)) {
                            $db = Db::connect();
                            $stmt = $db->prepare('update token_auth set isExpired=:isExpired where id=:tokenId');
                            $stmt->bindValue('isExpired', 1);
                            $stmt->bindValue('tokenId', $userToken->id);
                            $stmt->execute();
                        }
                        //insert new token
                        $db = Db::connect();
                        $stmt = $db->prepare('insert into token_auth (user,passwordHash,selectorHash,expiryDate) 
                                            values (:user,:passwordHash,:selectorHash,:expiryDate)');
                        $stmt->bindValue('user', $user->id);
                        $stmt->bindValue('passwordHash', $randomPasswordHash);
                        $stmt->bindValue('selectorHash', $randomSelectorHash);
                        $stmt->bindValue('expiryDate', $expiryDate);
                        $stmt->execute();

                    } else {
                        
                        //if user login without remember me checked unset cookies
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

                    header('Location: ' . App::config('url'));
                } else {
                    Session::getInstance()->addMessage('Incorrect combination of email/password', 'warning');
                    $this->index();
                }
            } else {
                Session::getInstance()->addMessage('Wrong mail', 'warning');
                $this->index();
            }
        } else {
            header('Location: ' . App::config('url') . 'login');
        }
    }

    /**
     * logout user from page and redirects to home page
     */
    public function logout()
    {
        Session::getInstance()->logout();
        Session::getInstance()->addMessage('You have successfully logged out', 'success');
        header('Location: ' . App::config('url'));
    }

    public function getToken($length)
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

    public function cryptoRandSecure($min, $max)
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
