<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 27.11.2016 г.
 * Time: 11:00
 */

namespace FPopov\Services;


interface UserServiceInterface
{
    public function login($username, $password) : bool;

    public function register($username, $password) : bool;
}