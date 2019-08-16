<?php

class LoginController
{
    public function index()
    {
        $view = new View();
        $view->render('login');
    }
}