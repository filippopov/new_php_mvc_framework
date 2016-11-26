<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 25.11.2016 г.
 * Time: 13:48
 */

namespace FPopov\Controllers;


use FPopov\Core\View;
use FPopov\Services\UserService;
use FPopov\UserExceptions\UserException;

class UsersController
{
    public function login()
    {
        $view = new View();

        $view->render('users/login');
    }

    public function loginPost()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $service = new UserService();

        $loginResult = $service->login($username, $password);

        if ($loginResult) {
            header('Location: profile');
            exit();
        }

        throw new UserException('Please enter valid data');
    }

    public function register()
    {
        $view = new View();

        $view->render('users/register');
    }

    public function registerPost()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $service = new UserService();

        $registerResult = $service->register($username, $password);

        if ($registerResult) {
            header('Location: login');
            exit();
        }

        throw new UserException('Please enter valid data');
    }

    public function profile()
    {
        $view = new View();

        $model = [
            'id' => $_SESSION['id']
        ];

        $view->render('users/profile', $model);
    }
}