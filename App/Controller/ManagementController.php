<?php 

class ManagementController
{

    /**
     * Renders management page
     */
    public function index()
    {
        if (!Session::getInstance()->isLoggedIn() && !Session::getInstance()->cookieLoggin()) {
            header('Location: ' . App::config('url'));
        } else {
            $db = Db::connect();
            $images = new Image($db);
            $images = $images->getImages();

            $view = new View();
            $view->render('management', [
                'images' => $images
            ]);
        }
        
    }

    /**
     * Add new image to uploads folder and path to database
     */
    public function addImage() {
        if (isset($_FILES['image'])) {
            $targetDir = BP . 'public/uploads/';
            $fileType = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $name = substr( base_convert( time(), 10, 36 ) . md5( microtime() ), 0, 16 ) . '.' . $fileType;
            $targetFile = $targetDir . $name;
            $allowedFileTypes = ['jpg','jpeg','png'];
            $valid = true;

            if (!in_array($fileType, $allowedFileTypes)) {
                $valid = false;
                Session::getInstance()->addMessage('jpg and png allowed only', 'warning');
                header('Location: ' . App::config('url') . 'management');
                exit();
            }

            if ($_FILES['image']['size'] > 2097152) {
                $valid = false;
                Session::getInstance()->addMessage('File size too big', 'warning');
                header('Location: ' . App::config('url') . 'management');
                exit();
            }

            if ($valid) {
                move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
                $db = Db::connect();
                $image = new Image($db);
                $image->addImage(Session::getInstance()->getUser()->id, $name);
                Session::getInstance()->addMessage('Image uploaded successfully', 'success');
                header('Location: ' . App::config('url') . 'management');
            }
        } else {
            Session::getInstance()->addMessage('Choose image to upload', 'warning');
            header('Location: ' . App::config('url') . 'management');
        }
    }

    /**
     * Removes image from uploads folder and database
     *
     * @param integer $id
     */
    public function removeImage(int $id)
    {
        $imgId = $id;
        $db = Db::connect();
        $image = new Image($db);
        $image = $image->findImgById($imgId);
        $imageName = $image->imgLocation;
        $targetDir = BP . 'public/uploads/';

        $userId = Session::getInstance()->getUser()->id;

        
        if ($userId != $image->user) {
            Session::getInstance()->logout();
            header('Location: ' . App::config('url'));
            exit();
        }

        if (file_exists($targetDir . $imageName)) {
            if (unlink($targetDir . $imageName)) {
                $remove = new Image($db);
                $remove->deleteImg($id);
                Session::getInstance()->addMessage('Image deleted successfully');
                header('Location: ' . App::config('url') . 'management');
            } else {
                Session::getInstance()->addMessage('Something went wrong, try again', 'warning');
            }
        }
        
        
    }
}