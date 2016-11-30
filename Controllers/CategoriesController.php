<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 10:34
 */

namespace FPopov\Controllers;


use FPopov\Core\ViewInterface;
use FPopov\Models\Binding\Category\CategoryAddBindingModel;
use FPopov\Services\Application\ResponseService;
use FPopov\Services\Application\ResponseServiceInterface;
use FPopov\Services\Category\CategoryServiceInterface;

class CategoriesController
{
    private $view;
    private $categoryService;
    private $responseService;

    public function __construct(ViewInterface $view, CategoryServiceInterface $categoryService, ResponseServiceInterface $responseService)
    {
        $this->view = $view;
        $this->categoryService = $categoryService;
        $this->responseService = $responseService;
    }

    public function add()
    {
        $this->view->render();
    }

    public function addPost(CategoryAddBindingModel $bindingModel)
    {
        $this->categoryService->add($bindingModel);
        $this->responseService->redirect('categories', 'view');
    }

    public function view()
    {
        $categories = $this->categoryService->findAll();

        $this->view->render($categories);
    }

    public function topics()
    {
        var_dump('Under development');
    }
}