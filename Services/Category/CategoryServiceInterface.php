<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 11:02
 */

namespace FPopov\Services\Category;


use FPopov\Models\Binding\Category\CategoryAddBindingModel;

interface CategoryServiceInterface
{
    public function add(CategoryAddBindingModel $bindingModel) : bool;

    public function findAll($params);

    public function edit($id);

    public function editPost($id, $name);

    public function addGridCategory();

    public function generateGridCategory($name);

    public function deleteCategory($id);
}