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
    public function render($templateName, $model = null);
}