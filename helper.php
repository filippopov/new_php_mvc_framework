<?php

function dd()
{
    echo '<pre>';
    array_map(function($x) { var_dump($x); }, func_get_args());
    die;
}

function clearInput($data)
{
    $data = trim(addslashes(strip_tags($data)));

    return $data;
}