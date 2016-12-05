<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 28.11.2016 г.
 * Time: 19:41
 */

namespace FPopov\Services\Application;


use FPopov\Core\MVC\MVCContext;

class ResponseService implements ResponseServiceInterface
{
    private $mvcContext;

    public function __construct(MVCContext $MVCContext)
    {
        $this->mvcContext = $MVCContext;
    }

    public function redirect($controller, $action, $params = [])
    {
        $url = $this->mvcContext->getUriJunk()
            . $controller
            . '/'
            . $action;

        if (! empty($params)) {
            $url .= '/' . implode('/', $params);
        }

        header("Location: $url");
        exit();
    }
}