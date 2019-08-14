<?php

class HomeController
{
    public function index()
    {

        $view = new View();

        $posts = [
            'First post',
            'Second post'
        ];

        $view->render('home', [
            "posts" => $posts
        ]);
    }
}