<?php

class RegistrationController
{
    /**
     * renders registration page
     */
    public function index()
    {
        $view = new View();
        $view->render('registration', [
            'data' => $_POST
        ]);
    }

    /**
     * handles user registration and redirects to home page on success and if failed redirects
     * to registration page with appropriate message
     */
    public function register()
    {

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

        if ($valid) {
            $db = Db::connect();
            $user = new User($db);
            $user->addUser($firstName, $lastName, $userName, $email, $address, $password);
            if (!$user) {
                Session::getInstance()->addMessage('Something went wrong try again', 'info');
                header("Location: " . App::config('url') . 'registration');
            } else {
                Session::getInstance()->addMessage('You registered successfuly', 'success');
                header("Location: " . App::config('url') . 'login');
            }
        } else {
            header("Location: " . App::config('url') . 'registration');
        }
    }
}