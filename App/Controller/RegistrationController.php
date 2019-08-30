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

        $firstName = isset($_POST['firstname']) ? trim($_POST['firstName']) : '';
        $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ?  trim($_POST['password']) : '';
        $rpassword = isset($_POST['rpassword']) ? trim($_POST['rpassword']) : '';
        $valid = true;

        if ($username === '') {
            $valid = false;
            Session::getInstance()->addMessage('Username required', 'warning');
        }

        if ($email === '') {
            $valid = false;
            Session::getInstance()->addMessage('Email required', 'warning');
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
            $stmt = $db->prepare('insert into user (firstname,lastname,username,email,password) 
                values (:firstname,:lastname,:username,:email,:password)');
            $stmt->bindValue('firstname', $firstName);
            $stmt->bindValue('lastname', $lastName);
            $stmt->bindValue('username', $username);
            $stmt->bindValue('email', $email);
            $stmt->bindValue('password', password_hash($password, PASSWORD_BCRYPT));
            if ($stmt->execute()) {
                Session::getInstance()->addMessage('You registered successfuly', 'success');
                header("Location: " . App::config('url') . 'login');
            } else {
                Session::getInstance()->addMessage('Something went wrong try again', 'info');
                header("Location: " . App::config('url') . 'registration');
            }
        } else {
            header("Location: " . App::config('url') . 'registration');
            
        }
    }
}