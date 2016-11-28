<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 28.11.2016 г.
 * Time: 16:57
 */

namespace FPopov\Services\Application;


interface EncryptionServiceInterface
{
    public function hash(string $password) : string;

    public function verify(string $password, string $hash) : bool;
}