<?php 

class ManagementController
{
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

    public function removeImage($id)
    {
        //todo: delete image from uploads
        $db = Db::connect();
        $remove = new Image($db);
        $remove->deleteImg($id);
        Session::getInstance()->addMessage('Image deleted successfully');
        header('Location: ' . App::config('url') . 'management');
    }
}