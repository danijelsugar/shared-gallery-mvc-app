<?php

namespace Gallery\Controller;

use Gallery\Core\Config;
use Gallery\Core\Db;
use Gallery\Core\Request;
use Gallery\Core\Response;
use Gallery\Core\Session;
use Gallery\Core\View;
use Gallery\Model\User;
use Gallery\Model\Token;

class AuthController
{
    public function login(Request $request, Response $response)
    {
        if ($request->getMethod() === 'post') {
            if (!isset($_POST['login'])) {
                Session::getInstance()->addMessage('Something went wrong try again', 'warning');
                $response->redirect('/login');
                exit();
            }

            $user = new User(Db::connect());
            $user = $user->authenticate($_POST['email'], $_POST['password']);

            if (!$user) {
                Session::getInstance()->addMessage('Incorrect combination of email/password', 'warning');
                $response->redirect('/login');
                exit();
            }

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
                setcookie('random_selector', $randomSelector, $cookieExpirationTime, '/');

                $randomPasswordHash = password_hash($randomPassword, PASSWORD_DEFAULT);
                $randomSelectorHash = password_hash($randomSelector, PASSWORD_DEFAULT);

                $expiryDate = date('Y-m-d H:i:s', $cookieExpirationTime);

                $token = new Token(Db::connect());
                $userToken = $token->getTokenByUserId($user->id, 0);

                if (!empty($userToken)) {
                    $token->setTokenExpired($userToken->id);
                }
                $token->insertNewToken($user->id, $randomPasswordHash, $randomSelectorHash, $expiryDate);
            } else {
                //if user login without remember me checked unset cookies
                $this->unsetRememberMeCookies();
            }
            $response->redirect('/');
        }

        $view = new View();
        $view->render('auth/login', [
            'url' => Config::get('url'),
            'sess' => Session::getInstance()
        ]);
    }

    /**
     * logout user from page and redirects to home page
     */
    public function logout(Request $request, Response $response)
    {
        $this->unsetRememberMeCookies();
        Session::getInstance()->logout();
        Session::getInstance()->addMessage('You have successfully logged out', 'success');
        $response->redirect('/');
    }

    public function registration(Request $request, Response $response)
    {
        if ($request->getMethod() === 'post') {
            $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
            $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
            $userName = isset($_POST['username']) ? trim($_POST['username']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $password = isset($_POST['password']) ?  trim($_POST['password']) : '';
            $rpassword = isset($_POST['rpassword']) ? trim($_POST['rpassword']) : '';
            $valid = true;

            if ($userName === '') {
                $valid = false;
                Session::getInstance()->addMessage('Username required', 'warning');
            }

            if ($email === '') {
                $valid = false;
                Session::getInstance()->addMessage('Email required', 'warning');
            }

            if ($address === '') {
                $valid = false;
                Session::getInstance()->addMessage('Address required', 'warning');
            }

            if ($password === '') {
                $valid = false;
                Session::getInstance()->addMessage('Password required', 'warning');
            }

            if ($rpassword === '') {
                $valid = false;
                Session::getInstance()->addMessage('Repeat password required', 'warning');
            }

            if ($rpassword !== $password) {
                $valid = false;
                Session::getInstance()->addMessage('Passwords not matching', 'warning');
            }

            if (!$valid) {
                $response->redirect('/registration');
                exit();
            }

            $db = Db::connect();
            $user = new User($db);
            $user->addUser($firstName, $lastName, $userName, $email, $address, $password);

            if (!$user) {
                Session::getInstance()->addMessage('Something went wrong try again', 'info');
                $response->redirect('/registration');
                exit();
            }

            Session::getInstance()->addMessage('You registered successfuly', 'success');
            $response->redirect('/login');
        }

        $view = new View();
        $view->render('auth/registration', [
            'data' => $_POST,
            'url' => Config::get('url'),
            'sess' => Session::getInstance()
        ]);
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

    /**
     * Unset remember me cookies
     */
    protected function unsetRememberMeCookies()
    {
        if (isset($_COOKIE['user_login'])) {
            setcookie('user_login', '', '-1', '/');
        }

        if (isset($_COOKIE['random_password'])) {
            setcookie('random_password', '', '-1', '/');
        }

        if (isset($_COOKIE['random_selector'])) {
            setcookie('random_selector', '', '-1', '/');
        }
    }
}
