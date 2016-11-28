<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 28.11.2016 г.
 * Time: 15:58
 */

namespace FPopov\Core\MVC;


class Session implements SessionInterface
{
    private $data = [];

    public function __construct(&$data)
    {
        $this->data = &$data;
    }

    public function get($key)
    {
        return $this->data[$key];
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function remove($key)
    {
        unset($this->data[$key]);
    }

    public function exists($key) : bool
    {
        return array_key_exists($key, $this->data);
    }

    public function destroy()
    {
        unset($this->data);
        session_destroy();
    }
}