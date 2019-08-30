<?php

/**
 * HomeController that renders front page
 */
class HomeController
{
    /**
     * renders home(landing) page
     */
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
        var_dump($_COOKIE);
    }

}