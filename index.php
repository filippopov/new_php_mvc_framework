<?php
include 'autoloader.php';
session_start();

$uri = $_SERVER['REQUEST_URI'];
$self = $_SERVER['PHP_SELF'];

$self = str_replace('index.php', '', $self);

$uri = str_replace($self, '', $uri);

$args = explode('/', $uri);
$controllerName = 'FPopov\\Controllers\\' . ucfirst(array_shift($args)) . 'Controller';
$actionName = array_shift($args);

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    call_user_func_array(
        [
            $controller,
            $actionName
        ],
        $args
    );
} else {
    echo "<h1>Page Not Found</h1>";
}