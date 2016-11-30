<?php

include 'autoloader.php';
include 'helper.php';

session_start();

$uri = $_SERVER['REQUEST_URI'];
$self = $_SERVER['PHP_SELF'];

$self = str_replace('index.php', '', $self);

$uri = str_replace($self, '', $uri);

$getParamsArray = explode('?', $uri);

$getParams = isset($getParamsArray[1]) ? $getParamsArray[1] : '';
$uri = isset($getParamsArray[0]) ? $getParamsArray[0] : '';

$args = explode('/', $uri);

$controllerName = array_shift($args);

$actionName = array_shift($args);

$dbInstanceName = 'default';

\FPopov\Adapter\Database::setInstance(
    \FPopov\Config\DbConfig::DB_HOST,
    \FPopov\Config\DbConfig::DB_USER,
    \FPopov\Config\DbConfig::DB_PASS,
    \FPopov\Config\DbConfig::DB_NAME,
    $dbInstanceName
);



$mvcContext = new \FPopov\Core\MVC\MVCContext($controllerName, $actionName, $self, $args, $getParams);

$app = new \FPopov\Core\Application($mvcContext);

$app->addClass(\FPopov\Core\MVC\MVCContext::class, $mvcContext);
$app->addClass(\FPopov\Adapter\DatabaseInterface::class, \FPopov\Adapter\Database::getInstance($dbInstanceName));
$app->addClass(\FPopov\Core\MVC\SessionInterface::class, new \FPopov\Core\MVC\Session($_SESSION));

$app->registerDependency(\FPopov\Core\ViewInterface::class, \FPopov\Core\View::class);
$app->registerDependency(\FPopov\Services\User\UserServiceInterface::class, \FPopov\Services\User\UserService::class);
$app->registerDependency(\FPopov\Services\Application\EncryptionServiceInterface::class, \FPopov\Services\Application\BCryptEncryptionService::class);
$app->registerDependency(\FPopov\Services\Application\AuthenticationServiceInterface::class, \FPopov\Services\Application\AuthenticationService::class);
$app->registerDependency(\FPopov\Services\Application\ResponseServiceInterface::class, \FPopov\Services\Application\ResponseService::class);
$app->registerDependency(\FPopov\Services\Category\CategoryServiceInterface::class, \FPopov\Services\Category\CategoryService::class);
$app->registerDependency(\FPopov\Repositories\User\UserRepositoryInterface::class, \FPopov\Repositories\User\UserRepository::class);
$app->registerDependency(\FPopov\Repositories\Categories\CategoryRepositoryInterface::class, \FPopov\Repositories\Categories\CategoryRepository::class);

$app->start();

//, ['username'=> 'Stela3', 'password' => password_hash('123', PASSWORD_BCRYPT)]
//$repository = new \FPopov\Repositories\User\UserRepository(\FPopov\Adapter\Database::getInstance($dbInstanceName));
/** @var \FPopov\Models\DB\User\User[] $res */
//$res = $repository->findByCondition(['username' => 'version1'], \FPopov\Models\DB\User\User::class);
//foreach ($res as $re){
//    dd($re);
//}
