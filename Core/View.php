<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 25.11.2016 г.
 * Time: 14:02
 */

namespace FPopov\Core;


use FPopov\Core\MVC\MVCContext;

class View implements ViewInterface
{
    const VIEWS_FOLDER = 'views';
    const PARTIALS_FOLDER = 'partials';
    const HEADER_NAME = 'header';
    const FOOTER_NAME = 'footer';
    const VIEW_EXTENSION = '.php';

    private $mvcContext;

    public function __construct(MVCContext $MVCContext)
    {
        $this->mvcContext = $MVCContext;
    }

    public function render($templateName = null, $model = null)
    {
        $controller = $this->mvcContext->getController();
        $action = $this->mvcContext->getAction();
        $uriJunk = $this->mvcContext->getUriJunk();

        if ($templateName === null ) {
            $templateName = $controller . DIRECTORY_SEPARATOR . $action;
        } elseif (! is_string($templateName)) {
            $model = $templateName;
            $templateName = $controller . DIRECTORY_SEPARATOR . $action;
        }

        include self::VIEWS_FOLDER
            . DIRECTORY_SEPARATOR
            . self::PARTIALS_FOLDER
            . DIRECTORY_SEPARATOR
            . self::HEADER_NAME
            . self::VIEW_EXTENSION;


        include self::VIEWS_FOLDER
            . DIRECTORY_SEPARATOR
            . $templateName
            . self::VIEW_EXTENSION;

        include self::VIEWS_FOLDER
            . DIRECTORY_SEPARATOR
            . self::PARTIALS_FOLDER
            . DIRECTORY_SEPARATOR
            . self::FOOTER_NAME
            . self::VIEW_EXTENSION;
    }

    public function uri($controller, $action, $params = [])
    {
        $url = $this->mvcContext->getUriJunk()
            . $controller
            . DIRECTORY_SEPARATOR
            . $action;

        if (! empty($params)) {
            $url .= DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $params);
        }

        return $url;
    }
}