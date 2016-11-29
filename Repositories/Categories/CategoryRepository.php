<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 22:30
 */

namespace FPopov\Repositories\Categories;


use FPopov\Repositories\AbstractRepository;

class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    public function setOptions()
    {
        return array(
            'tableName' => 'categories',
            'primaryKeyName' => 'id'
        );
    }
}