<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 11:01
 */

namespace FPopov\Services\Category;


use FPopov\Adapter\DatabaseInterface;
use FPopov\Models\Binding\Category\CategoryAddBindingModel;
use FPopov\Models\DB\Category\Category;
use FPopov\Repositories\Categories\CategoryRepository;
use FPopov\Repositories\Categories\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface
{
    private $db;

    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct(DatabaseInterface $db, CategoryRepositoryInterface $categoryRepository)
    {
        $this->db = $db;
        $this->categoryRepository = $categoryRepository;
    }

    public function add(CategoryAddBindingModel $bindingModel) : bool
    {
        $categoryParams = [
            'name' => $bindingModel->getName()
        ];

        return $this->categoryRepository->create($categoryParams);
    }

    public function findAll()
    {
        return $this->categoryRepository->findAll(Category::class);
    }
}