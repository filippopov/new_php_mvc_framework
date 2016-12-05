<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 29.11.2016 г.
 * Time: 22:30
 */

namespace FPopov\Repositories\Categories;


interface CategoryRepositoryInterface
{
    public function testGrid($params = array());

    public function testGridCount($params = array());
}