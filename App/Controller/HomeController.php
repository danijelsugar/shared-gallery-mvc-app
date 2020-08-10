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
        $view->render('home', [
    
        ]);
    }

    public function numberOfImages()
    {
        $db = Db::connect();
        $image = new Image($db);
        $image = $image->getImageCount();

        echo json_encode($image);
    }

}