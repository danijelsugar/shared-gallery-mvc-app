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

            $view = new View();
            $view->render('account', [
                'user' => $user
            ]);
        }
    }

    public function editUser()
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
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $valid = true;

            if ($username === '') {
                $valid = false;
                Session::getInstance()->addMessage('Username required', 'warning');
            }

            if ($valid) {
                $db = Db::connect();
                $user = new User($db);
                $user->editUser($firstName, $lastName, $username, $address, $id);
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

    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: '. App::config('url') . 'account');
            exit();
        }

        if (isset($_POST['changePassword'])) {
            $db = Db::connect();
            $user = new User($db);
            $userPassword = $user->getPasswordById($_POST['id']);
            $currentPassword = $_POST['currentPassword'];

            $newPassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
            $repeatNewPassword = password_hash($_POST['repeatNewPassword'], PASSWORD_BCRYPT);

            if ($currentPassword === '' || $newPassword === '' || $repeatNewPassword === '') {
                Session::getInstance()->addMessage('All field are required to change password', 'warning');
                header('Location: ' . App::config('url') . 'account');
                exit();
            }

            if (!$userPassword || !password_verify($currentPassword,$userPassword->password)) {
                Session::getInstance()->addMessage('Wrong current password', 'warning');
                header('Location: ' . App::config('url') . 'account');
                exit();
            }

            if ($_POST['newPassword'] != $_POST['repeatNewPassword']) {
                Session::getInstance()->addMessage('New password and repeat password does not match', 'warning');
                header('Location: ' . App::config('url') . 'account');
                exit();
            }

            $user->changePassword($newPassword, $_POST['id']);
            Session::getInstance()->addMessage('Password changed successfully');
            header('Location: ' . App::config('url') . 'account');
            
        }
        
    }
}