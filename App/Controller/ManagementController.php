<?php 

class ManagementController
{
    public function index()
    {
        if (!Session::getInstance()->isLoggedIn() && !Session::getInstance()->cookieLoggin()) {
            header('Location: ' . App::config('url'));
        } else {
            var_dump(Session::getInstance()->getUser()->id);
            $view = new View();
            $view->render('management', [
                
            ]);
        }
        
    }

    public function addImage() {
        if (isset($_FILES['image'])) {
            $targetDir = BP . 'public/uploads/';
            $name = basename($_FILES['image']['name']);
            $targetFile = $targetDir . $name;
            $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);
            $allowedFileTypes = ['jpg','jpeg','png'];
            $valid = true;
            var_dump($targetFile);

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
}