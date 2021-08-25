<?php

namespace Gallery\Controller;

use Gallery\Core\Config;
use Gallery\Core\Db;
use Gallery\Core\Pagination;
use Gallery\Core\Request;
use Gallery\Core\Response;
use Gallery\Core\Session;
use Gallery\Core\View;
use Gallery\Model\Image;

class ManagementController
{

    /**
     * Renders management page
     */
    public function management(Request $request, Response $response)
    {
        if (!Session::getInstance()->isLoggedIn()) {
            $response->redirect('/');
            exit();
        }

        $db = Db::connect();
        $images = new Image($db);

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        $pagesLimit = Config::get('pagesLimit');
        $pagination = new Pagination($images, $pagesLimit);
        $numberOfPages = $pagination->getPageNumber();
        $offset = $pagination->getOffset($page);

        $images = $images->getImages($pagesLimit, $offset);

        $view = new View();
        $view->render('management/management', [
            'images' => $images,
            'url' => Config::get('url'),
            'sess' => Session::getInstance(),
            'numOfPages' => $numberOfPages,
            'currentPage' => $page
        ]);
    }

    /**
     * Add new image to uploads folder and path to database
     */
    public function addImage(Request $request, Response $response)
    {

        if (!isset($_FILES['image']) || !file_exists($_FILES['image']['tmp_name'])) {
            Session::getInstance()->addMessage('Choose image to upload', 'warning');
            $response->redirect('/management');
            exit();
        }

        $targetDir = BP . 'public/uploads/';
        $fileType = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $name = substr(base_convert(time(), 10, 36) . md5(microtime()), 0, 16) . '.' . $fileType;
        $targetFile = $targetDir . $name;
        $allowedFileTypes = ['jpg', 'jpeg', 'png'];

        if (!in_array($fileType, $allowedFileTypes)) {
            Session::getInstance()->addMessage('jpg and png allowed only', 'warning');
            $response->redirect('/management');
            exit();
        }

        if ($_FILES['image']['size'] > 2097152) {
            Session::getInstance()->addMessage('File size too big', 'warning');
            $response->redirect('/management');
            exit();
        }

        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $db = Db::connect();
        $image = new Image($db);
        $image->addImage(Session::getInstance()->getUser()->id, $name);
        Session::getInstance()->addMessage('Image uploaded successfully', 'success');
        $response->redirect('/management');
    }

    
    public function removeImage(Request $request, Response $response)
    {
        $imgId = $_POST['id'];
        $db = Db::connect();
        $image = new Image($db);
        $image = $image->findImgById($imgId);
        $imageName = $image->imgLocation;
        $targetDir = BP . 'public/uploads/';

        $userId = Session::getInstance()->getUser()->id;

        if ($userId != $image->user) {
            Session::getInstance()->logout();
            $response->redirect('/');
            exit();
        }

        if (file_exists($targetDir . $imageName)) {
            if (unlink($targetDir . $imageName)) {
                $remove = new Image($db);
                $remove->deleteImg($imgId);
                Session::getInstance()->addMessage('Image deleted successfully'); 
            } else {
                Session::getInstance()->addMessage('Something went wrong, try again', 'warning');
            }
        } else {
            $remove = new Image($db);
            $remove->deleteImg($imgId);
            Session::getInstance()->addMessage('Image deleted successfully'); 
        }
        $response->redirect('/management');
    }
}
