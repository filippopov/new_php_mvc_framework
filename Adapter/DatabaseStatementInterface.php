<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 27.11.2016 г.
 * Time: 10:52
 */

namespace FPopov\Adapter;


interface DatabaseStatementInterface
{
    public function execute(array $args = []): bool;

    public function fetch();

    public function fetchAll();

    public function fetchObject(string $class);

    public function rowCount();
}