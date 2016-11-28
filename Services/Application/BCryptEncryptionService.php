<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 28.11.2016 г.
 * Time: 16:59
 */

namespace FPopov\Services\Application;


class BCryptEncryptionService implements EncryptionServiceInterface
{
    public function hash(string $password) : string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(string $password, string $hash) : bool
    {
        return password_verify($password, $hash);
    }
}