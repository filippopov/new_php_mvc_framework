<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 26.11.2016 г.
 * Time: 11:54
 */

namespace FPopov\Core;


use FPopov\Core\MVC\MVCContextInterface;
use FPopov\UserExceptions\ApplicationException;

class Application
{
    const VENDOR_NAMESPACE = 'FPopov';
    const CONTROLLER_NAMESPACE = 'Controllers';
    const CONTROLLERS_SUFFIX = 'Controller';
    const NAMESPACE_SEPARATOR = '\\';


    private $mvcContext;

    private $dependencies = [];
    private $resolveDependencies = [];

    public function __construct(MVCContextInterface $mvcContext)
    {
        $this->mvcContext = $mvcContext;
    }

    public function start()
    {
        $controllerName = $this->mvcContext->getController();
        $actionName = $this->mvcContext->getAction();
        $args = $this->mvcContext->getArguments();

        $controllerFullNameWithNamespace =
            self::VENDOR_NAMESPACE
            . self::NAMESPACE_SEPARATOR
            . self::CONTROLLER_NAMESPACE
            . self::NAMESPACE_SEPARATOR
            . ucfirst($controllerName)
            . self::CONTROLLERS_SUFFIX;

        $refMethod = new \ReflectionMethod($controllerFullNameWithNamespace, $actionName);

        $parameters = $refMethod->getParameters();

        foreach ($parameters as $parameter) {
            $parameterClass = $parameter->getClass();
            if($parameterClass !== null) {
                $className = $parameterClass->getName();
                $instance = new $className();
                foreach ($parameterClass->getProperties() as $field) {
                    $field->setAccessible(true);
                    if (array_key_exists($field->getName(), $_POST)) {
                        $field->setValue($instance, $_POST[$field->getName()]);
                    }

                }
                $args[] = $instance;
            }
        }

        if (class_exists($controllerFullNameWithNamespace)) {
            $controller = new $controllerFullNameWithNamespace();
            call_user_func_array(
                [
                    $controller,
                    $actionName
                ],
                $args
            );
        }
    }

    private function resolve($className)
    {
        $refClass = new \ReflectionClass($className);
        $constructor = $refClass->getConstructor();

        if ($constructor === null) {
            $instance = new $className();
            return $instance;
        }

        $parameters = $constructor->getParameters();
        $parameterToInstantiate = [];

        foreach ($parameters as $parameter) {
            $interface = $parameter->getClass();
            if ($interface === null) {
                throw new ApplicationException('Parameters can not be primitive to order DI to work');
            }

            $interfaceName = $interface->getName();

            $implementation = $this->dependencies[$interfaceName];

            if (array_key_exists($implementation, $this->resolveDependencies)) {
                $implementationInstance = $this->resolveDependencies[$implementation];
            } else {
                $implementationInstance = $this->resolve($implementation);
                $this->resolveDependencies[$implementation] = $implementationInstance;
            }

            $parameterToInstantiate[] = $implementationInstance;
        }

        $result = $refClass->newInstance($parameterToInstantiate);
        $this->resolveDependencies[$className] = $result;

        return $result;
    }
}