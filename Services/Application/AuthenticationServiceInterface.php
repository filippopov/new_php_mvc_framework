<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 28.11.2016 г.
 * Time: 19:00
 */

namespace FPopov\Services\Application;


interface AuthenticationServiceInterface
{
    public function isAuthenticated() : bool;

    public function logout();

    public function login($username, $password) : bool;

    public function getUserId();
}