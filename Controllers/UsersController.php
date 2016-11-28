<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 25.11.2016 г.
 * Time: 13:48
 */

namespace FPopov\Controllers;


use FPopov\Core\MVC\SessionInterface;
use FPopov\Core\ViewInterface;
use FPopov\Models\Binding\User\UserLoginBindingModel;
use FPopov\Models\Binding\User\UserRegisterBindingModel;
use FPopov\Models\View\ApplicationViewModel;
use FPopov\Services\UserServiceInterface;
use FPopov\UserExceptions\UserException;

class UsersController
{
    private $view;
    private $service;

    public function __construct(ViewInterface $view, UserServiceInterface $service)
    {
        $this->view = $view;
        $this->service = $service;
    }

    public function login()
    {
        $this->view->render();
    }

    public function loginPost(UserLoginBindingModel $bindingModel)
    {
        $username = $bindingModel->getUsername();
        $password = $bindingModel->getPassword();

        $loginResult = $this->service->login($username, $password);

        if ($loginResult) {
            header('Location: profile');
            exit();
        }

        throw new UserException('Please enter valid data');
    }

    public function register()
    {
        $viewModel = new ApplicationViewModel('Blog');

        $this->view->render($viewModel);
    }

    public function registerPost(UserRegisterBindingModel $bindingModel)
    {
        $username = $bindingModel->getUsername();
        $password = $bindingModel->getPassword();

        $registerResult = $this->service->register($username, $password);

        if ($registerResult) {
            header('Location: login');
            exit();
        }

        throw new UserException('Please enter valid data');
    }

    public function profile(SessionInterface $session)
    {
        $model = [
            'id' => $session->get('id')
        ];

        $this->view->render($model);
    }
}