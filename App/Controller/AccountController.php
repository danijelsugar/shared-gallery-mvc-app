<?php 

class AccountController
{
    public function index()
    {
        if (!Session::getInstance()->isLoggedIn()) {
            header('Location: ' . App::config('url'));
        } else {
            $view = new View();
            $view->render('account', [
                
            ]);
        }
    }
}