<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 25.11.2016 г.
 * Time: 13:48
 */

namespace FPopov\Controllers;


use FPopov\Core\ViewInterface;
use FPopov\Models\Binding\User\UserLoginBindingModel;
use FPopov\Models\Binding\User\UserRegisterBindingModel;
use FPopov\Models\View\ApplicationViewModel;
use FPopov\Services\UserServiceInterface;
use FPopov\UserExceptions\UserException;

class UsersController
{
    public function login(ViewInterface $view)
    {
        $view->render();
    }

    public function loginPost(UserLoginBindingModel $bindingModel, UserServiceInterface $service)
    {
        $username = $bindingModel->getUsername();
        $password = $bindingModel->getPassword();

        $loginResult = $service->login($username, $password);

        if ($loginResult) {
            header('Location: profile');
            exit();
        }

        throw new UserException('Please enter valid data');
    }

    public function register(ViewInterface $view)
    {
        $viewModel = new ApplicationViewModel('Blog');

        $view->render($viewModel);
    }

    public function registerPost(UserRegisterBindingModel $bindingModel, UserServiceInterface $service)
    {
        $username = $bindingModel->getUsername();
        $password = $bindingModel->getPassword();

        $registerResult = $service->register($username, $password);

        if ($registerResult) {
            header('Location: login');
            exit();
        }

        throw new UserException('Please enter valid data');
    }

    public function profile(ViewInterface $view)
    {
        $model = [
            'id' => $_SESSION['id']
        ];

        $view->render($model);
    }
}