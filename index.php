<?php
include 'autoloader.php';
session_start();

$uri = $_SERVER['REQUEST_URI'];
$self = $_SERVER['PHP_SELF'];

$self = str_replace('index.php', '', $self);

$uri = str_replace($self, '', $uri);

$args = explode('/', $uri);

$controllerName = array_shift($args);

$actionName = array_shift($args);

$mvcContext = new \FPopov\Core\MVC\MVCContext($controllerName, $actionName, $self, $args);

$app = new \FPopov\Core\Application($mvcContext);

$app->start();