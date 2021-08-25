<?php

namespace Gallery\Controller;

use Gallery\Core\Config;
use Gallery\Core\Session;
use Gallery\Core\View;
use Gallery\Core\Db;
use Gallery\Core\Request;
use Gallery\Core\Response;
use Gallery\Model\User;

class AccountController
{
    public function account(Request $request, Response $response)
    {
        if (!Session::getInstance()->isLoggedIn()) {
            $response->redirect('/');
            exit();
        }

        $db = Db::connect();
        $userId = Session::getInstance()->getUser()->id;
        $user = new User($db);
        $user = $user->findById($userId);

        $view = new View();
        $view->render('account/account', [
            'user' => $user,
            'url' => Config::get('url'),
            'sess' => Session::getInstance()
        ]);
    }

    public function editProfile(Request $request, Response $response)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $response->redirect('/account');
            exit();
        }

        if (isset($_POST['edit'])) {
            $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
            $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
            $username = isset($_POST['userName']) ? trim($_POST['userName']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $id = isset($_POST['id']) ? $_POST['id'] : '';

            if ($id !== Session::getInstance()->getUser()->id) {
                $response->redirect('/account');
                exit();
            }

            if ($username === '') {
                Session::getInstance()->addMessage('Username required', 'warning');
                $response->redirect('/account');
                exit();
            }

            $db = Db::connect();
            $user = new User($db);
            $user->editUser($firstName, $lastName, $username, $address, $id);

            if (!$user) {
                Session::getInstance()->addMessage('Something went wrong try again', 'info');
                $response->redirect('/account');
            }

            Session::getInstance()->addMessage('Successfuly edited your profile', 'success');
            $response->redirect('/account');
        }
    }

    public function changePassword(Request $request, Response $response)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $response->redirect('/account');
            exit();
        }

        if (isset($_POST['changePassword'])) {
            $db = Db::connect();
            $user = new User($db);
            $userPassword = $user->getPasswordById($_POST['id']);
            $currentPassword = $_POST['currentPassword'];

            if ($_POST['id'] !== Session::getInstance()->getUser()->id) {
                $response->redirect('/account');
                exit();
            }

            $newPassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);
            $repeatNewPassword = password_hash($_POST['repeatNewPassword'], PASSWORD_BCRYPT);

            if ($currentPassword === '' || $newPassword === '' || $repeatNewPassword === '') {
                Session::getInstance()->addMessage('All field are required to change password', 'warning');
                $response->redirect('/account');
                exit();
            }

            if (!$userPassword || !password_verify($currentPassword, $userPassword->password)) {
                Session::getInstance()->addMessage('Wrong current password', 'warning');
                $response->redirect('/account');
                exit();
            }

            if ($_POST['newPassword'] != $_POST['repeatNewPassword']) {
                Session::getInstance()->addMessage('New password and repeat password does not match', 'warning');
                $response->redirect('/account');
                exit();
            }

            $user->changePassword($newPassword, $_POST['id']);
            Session::getInstance()->addMessage('Password changed successfully');
            $response->redirect('/account');
        }
    }
}
