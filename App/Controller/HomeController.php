<?php

namespace Gallery\Controller;

use Gallery\Core\Config;
use Gallery\Core\View;
use Gallery\Core\Db;
use Gallery\Core\Session;
use Gallery\Model\Image;

/**
 * HomeController that renders front page
 */
class HomeController
{
    /**
     * renders home(landing) page
     */
    public function home()
    {
        $view = new View();
        $view->render('home/home', [
            'id' => 5,
            'url' => Config::get('url'),
            'sess' => Session::getInstance()
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
