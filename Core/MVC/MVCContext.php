<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 26.11.2016 г.
 * Time: 11:56
 */

namespace FPopov\Core\MVC;


class MVCContext implements MVCContextInterface
{
    private $controller;

    private $action;

    private $arguments = [];

    private $uriJunk;

    /**
     * MVCContext constructor.
     * @param $controller
     * @param $action
     * @param array $arguments
     * @param $uriJunk
     */
    public function __construct(string $controller, string $action, string $uriJunk, array $arguments)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->arguments = $arguments;
        $this->uriJunk = $uriJunk;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController(string $controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getUriJunk(): string
    {
        return $this->uriJunk;
    }

    /**
     * @param string $uriJunk
     */
    public function setUriJunk(string $uriJunk)
    {
        $this->uriJunk = $uriJunk;
    }

}