<?php

declare(strict_types=1);

//root path
define('BP', dirname(dirname(__FILE__)) . '/');

//enabling displaying php errors
ini_set('display_errors', "1");
error_reporting(E_ALL);

include __DIR__ . '/../vendor/autoload.php';

use Gallery\Controller\AccountController;
use Gallery\Controller\HomeController;
use Gallery\Controller\ManagementController;
use Gallery\Controller\AuthController;
use Gallery\Core\App;

$app = new App();

$app->router
    ->get('/', [HomeController::class, 'home'])
    ->post('/number-of-images', [HomeController::class, 'numberOfImages'])
    ->get('/login', [AuthController::class, 'login'])
    ->post('/login', [AuthController::class, 'login'])
    ->get('/logout', [AuthController::class, 'logout'])
    ->get('/registration', [AuthController::class, 'registration'])
    ->post('/registration', [AuthController::class, 'registration'])
    ->get('/management', [ManagementController::class, 'management'])
    ->post('/add-image', [ManagementController::class, 'addImage'])
    ->post('/remove-image', [ManagementController::class, 'removeImage'])
    ->get('/account', [AccountController::class, 'account'])
    ->post('/edit-profile', [AccountController::class, 'editProfile'])
    ->post('/change-password', [AccountController::class, 'changePassword']);

$app->run();
