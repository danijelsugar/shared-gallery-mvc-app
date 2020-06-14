<?php 

class AccountController
{
    public function index()
    {
        if (!Session::getInstance()->isLoggedIn() && !Session::getInstance()->cookieLoggin()) {
            header('Location: ' . App::config('url'));
        } else {
            $db = Db::connect();
            $userId = Session::getInstance()->getUser()->id;
            $user = new User($db);
            $user = $user->findById($userId);

            var_dump($user);

            $view = new View();
            $view->render('account', [
                'user' => $user
            ]);
        }
    }

    public function editUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: '. App::config('url') . 'account');
            exit();
        }

        if (isset($_POST['edit'])) {
            $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
            $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
            $username = isset($_POST['userName']) ? trim($_POST['userName']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $valid = true;

            var_dump($_POST);

            if ($username === '') {
                $valid = false;
                Session::getInstance()->addMessage('Username required', 'warning');
            }

            if ($valid) {
                $db = Db::connect();
                $user = new User($db);
                $user->editUser($firstName, $lastName, $username, $address);
                if (!$user) {
                    Session::getInstance()->addMessage('Something went wrong try again', 'info');
                    header("Location: " . App::config('url') . 'account');
                } else {
                    Session::getInstance()->addMessage('Successfuly edited your profile', 'success');
                    header("Location: " . App::config('url') . 'account');
                }
            } else {
                header('Location: '. App::config('url') . 'account');
            }
        }
    }
}