<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 26.11.2016 г.
 * Time: 12:08
 */

namespace FPopov\Core\MVC;


interface MVCContextInterface
{
    public function getController() : string;

    public function getAction() : string;

    public function getUriJunk() : string;

    public function getArguments() : array;

    public function getGetParams() : array;

    public function getOneGetParam(string $key) : string;
}