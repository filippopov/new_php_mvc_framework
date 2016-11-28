<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 27.11.2016 г.
 * Time: 11:00
 */

namespace FPopov\Services\User;


interface UserServiceInterface
{
    public function register($username, $password) : bool;
}