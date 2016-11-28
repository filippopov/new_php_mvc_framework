<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 28.11.2016 г.
 * Time: 15:58
 */

namespace FPopov\Core\MVC;


interface SessionInterface
{
    public function get($key);

    public function set($key, $value);

    public function remove($key);

    public function exists($key) : bool;

    public function destroy();
}