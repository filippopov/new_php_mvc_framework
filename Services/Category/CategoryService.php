<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 11:01
 */

namespace FPopov\Services\Category;


use FPopov\Adapter\DatabaseInterface;
use FPopov\Core\MVC\Message;
use FPopov\Core\ViewInterface;
use FPopov\Models\Binding\Category\CategoryAddBindingModel;
use FPopov\Repositories\Categories\CategoryRepository;
use FPopov\Repositories\Categories\CategoryRepositoryInterface;
use FPopov\Services\AbstractService;

class CategoryService extends AbstractService implements CategoryServiceInterface
{
    private $db;

    /** @var CategoryRepository */
    private $categoryRepository;

    private $view;

    public function __construct(DatabaseInterface $db, CategoryRepositoryInterface $categoryRepository, ViewInterface $view)
    {
        $this->db = $db;
        $this->categoryRepository = $categoryRepository;
        $this->view = $view;
    }

    public function add(CategoryAddBindingModel $bindingModel) : bool
    {
        $categoryParams = [
            'name' => $bindingModel->getName()
        ];

        return $this->categoryRepository->create($categoryParams);
    }

    public function findAll($params)
    {
        $bindFilter = $this->getParamFilters($params);

        $structure = [
            'id' => [
                'title' => 'Id',
                'type' => self::TYPE_DATA
            ],
            'name' => [
                'title' => 'Name',
                'type' => self::TYPE_DATA
            ],
            'actions' => array(
                'title' => 'Actions',
                'type' => self::TYPE_ACTIONS,
                'values' => array(
                    'edit' => function ($row) {
                        return $this->view->uri('categories', 'edit', [], ['id' => $row['id']]);
                    },
                    'delete' => function  ($row) {
                        return $this->view->uri('categories', 'deleteCategory', [], ['id' => $row['id']]);
                    }
                )
            )
        ];

        $repoData = $this->categoryRepository->testGrid($bindFilter);
        $bindFilter['total'] = $this->categoryRepository->testGridCount($bindFilter);
        $data = $this->generateGridData($structure, $repoData);

        $searchFields = [
            'id' => 'Id',
            'name' => 'Name Category'
        ];

        $table = [
            'tableSearchFields' => $searchFields,
            'tableData' => $data,
            'filter' => $this->pageFilters($bindFilter),
        ];

        return $table;
    }

    public function edit($id)
    {
        $data = $this->categoryRepository->findOneRowById($id);

        $structure = $this->getModuleFields();

        return [
            'formFieldData' => self::generateFormData($structure, $data)
        ];
    }

    public function editPost($id, $name)
    {
        $category = $this->categoryRepository->findByCondition(['name' => $name]);

        if (! empty($category)) {
            Message::setError('Category name already exist');
            return false;
        }

        $result = $this->categoryRepository->update($id, ['name' => $name]);

        if (! $result) {
            Message::setError('Can not update this row');
            return false;
        }

        Message::postMessage('Successfully update this row', Message::POSITIVE_MESSAGE);
        return true;
    }

    public function deleteCategory($id)
    {
        $result = $this->categoryRepository->delete($id);

        if (! $result) {
            Message::postMessage('Can not delete this row', Message::NEGATIVE_MESSAGE);
            return false;
        }

        Message::postMessage('Successfully delete', Message::POSITIVE_MESSAGE);
        return true;
    }

    public function addGridCategory()
    {
        $structure = $this->getModuleFieldsAdd();

        return [
            'formFieldData' => self::generateFormData($structure)
        ];
    }

    public function generateGridCategory($name)
    {
        $isExistCategoryName = $this->categoryRepository->findByCondition(['name' => $name]);

        if (! empty($isExistCategoryName)) {
            Message::setError('Category name exist, please try with other name');
            return false;
        }

        $result = $this->categoryRepository->create(['name' => $name]);

        if (! $result) {
            Message::setError('Something go wrong :)');
            return false;
        }

        Message::postMessage('Successfully create category', Message::POSITIVE_MESSAGE);
        return true;
    }

    protected function getModuleFields()
    {
        $structure = [
            'id' => [
                'title' => 'Id',
                'type' => self::TYPE_INPUT,
                'inputValidate' => self::TYPE_INPUT
            ],
            'name' => [
                'title' => 'name',
                'type' => self::TYPE_INPUT,
                'inputValidate' => self::TYPE_INPUT
            ]
        ];

        return $structure;
    }

    protected function getModuleFieldsAdd()
    {
        $structure = [
            'name' => [
                'title' => 'name',
                'type' => self::TYPE_INPUT,
                'inputValidate' => self::TYPE_INPUT
            ]
        ];

        return $structure;
    }
}