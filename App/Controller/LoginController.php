<?php

class LoginController
{
    public function index()
    {
        $view = new View();
        $view->render('login');
    }

    public function authorize()
    {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ?  trim($_POST['password']) : '';
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

    public function logout()
    {
        Session::getInstance()->logout();
        Session::getInstance()->addMessage('You have successfully logged out', 'success');
        header('Location: ' . App::config('url'));
    }
}