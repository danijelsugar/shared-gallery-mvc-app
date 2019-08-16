<?php

class RegistrationController
{
    public function index()
    {
        $view = new View();
        $view->render('registration');
    }

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
            
        }
    }
}