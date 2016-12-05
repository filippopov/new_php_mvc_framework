<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 27.11.2016 г.
 * Time: 0:13
 */

namespace FPopov\Core;


interface ViewInterface
{
    public function render($params = array());

    public function uri($controller, $action, $params = [], $getParams = '');

    public function generateUriWithOrderParams($fieldName, $filter = array());

    public function generatePageUrl($page, $filter = array());

    public function generatePageUrlCounter($onPage, $filter = array());

    public function urlSearch($filter = array());
}