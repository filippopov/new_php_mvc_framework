<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 11:01
 */

namespace FPopov\Services\Category;


use FPopov\Adapter\DatabaseInterface;
use FPopov\Core\View;
use FPopov\Models\Binding\Category\CategoryAddBindingModel;
use FPopov\Models\DB\Category\Category;
use FPopov\Repositories\Categories\CategoryRepository;
use FPopov\Repositories\Categories\CategoryRepositoryInterface;
use FPopov\Services\AbstractService;

class CategoryService extends AbstractService implements CategoryServiceInterface
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
        $aCollStruct = [
            'id' => [
                'title' => 'Id',
                'type' => self::TYPE_DATA
            ],
            'name' => [
                'title' => 'Name',
                'type' => self::TYPE_DATA
            ]
        ];

        $repoData = $this->categoryRepository->testGrid();

        $data = $this->generateGridData($aCollStruct, $repoData);

        $table = [
            'tableData' => $data
        ];

        return $table;

//        return $this->categoryRepository->findAll(Category::class);
    }

}



//$table = array(
//    'tableSearchFields' => $aSearchFields,
//    'tableOrderFields' => $aOrderColls,
//    'tableData' => $aOrders,
//    'filter' => $this->returnFilters($bindFilter),
//    'navigation' => $navigationInfo
//);
//
//return $table;