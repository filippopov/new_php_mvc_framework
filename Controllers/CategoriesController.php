<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 10:34
 */

namespace FPopov\Controllers;


use FPopov\Core\MVC\Message;
use FPopov\Core\MVC\MVCContext;
use FPopov\Core\ViewInterface;
use FPopov\Models\Binding\Category\CategoryAddBindingModel;
use FPopov\Models\Binding\Category\CategoryEditBindingModel;
use FPopov\Services\Application\ResponseServiceInterface;
use FPopov\Services\Category\CategoryServiceInterface;

class CategoriesController
{
    private $view;
    private $categoryService;
    private $responseService;
    private $MVCContext;

    public function __construct(ViewInterface $view, CategoryServiceInterface $categoryService, ResponseServiceInterface $responseService, MVCContext $MVCContext)
    {
        $this->view = $view;
        $this->categoryService = $categoryService;
        $this->responseService = $responseService;
        $this->MVCContext = $MVCContext;
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
        $params = $this->MVCContext->getGetParams();
        $categories = $this->categoryService->findAll($params);
        $params = ['model' => $categories];
        $this->view->render($params);
    }

    public function edit(CategoryEditBindingModel $bindingModel)
    {
        $id = $this->MVCContext->getOneGetParam('id');
        $bindingId = $bindingModel->getId();
        $name = $bindingModel->getName();
        if (! empty($bindingId) && ! empty($name)) {
            $categoryUpdate = $this->categoryService->editPost($id, $name);
            $result['success'] = $categoryUpdate ? true : false;

            if (! $categoryUpdate) {
                $result['message'] = Message::returnMessages();
            }
            echo json_encode($result);
            die();
        }

        $editCategory = $this->categoryService->edit($id);

        $params = [
            'model' => $editCategory,
            'withHeader' => false,
            'withFooter' => false,
            'isMessage' => false
        ];

        $this->view->render($params);
    }

    public function deleteCategory()
    {
        $id = $this->MVCContext->getOneGetParam('id');
        $result = $this->categoryService->deleteCategory($id);

        return $result;
    }

    public function addGridCategory(CategoryAddBindingModel $bindingModel)
    {
        $name = $bindingModel->getName();
        if (! empty($name)) {
            $categoryCreate = $this->categoryService->generateGridCategory($name);
            $result['success'] = $categoryCreate ? true : false;

            if (! $categoryCreate) {
                $result['message'] = Message::returnMessages();
            }

            echo json_encode($result);
            die();
        }

        $addGridCategory = $this->categoryService->addGridCategory();

        $params = [
            'model' => $addGridCategory,
            'withHeader' => false,
            'withFooter' => false,
            'isMessage' => false
        ];

        $this->view->render($params);
    }

    public function topics()
    {
        var_dump('Under development');
    }
}