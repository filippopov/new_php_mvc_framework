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
    const MESSAGE_NAME = 'message';
    const VIEW_EXTENSION = '.php';

    private $mvcContext;

    public function __construct(MVCContext $MVCContext)
    {
        $this->mvcContext = $MVCContext;
    }

    public function render($params = array())
    {
        $templateName = isset($params['templateName']) ? $params['templateName'] : '';
        $model = isset($params['model']) ? $params['model'] : array();
        $withHeader = isset($params['withHeader']) ? $params['withHeader'] : true;
        $withFooter = isset($params['withFooter']) ? $params['withFooter'] : true;
        $isMessage = isset($params['isMessage']) ? $params['isMessage'] : true;
        $isToEscape = isset($params['isEscape']) ? $params['isEscape'] : true;

        $controller = $this->mvcContext->getController();
        $action = $this->mvcContext->getAction();
        $uriJunk = $this->mvcContext->getUriJunk();
        $getParams = $this->mvcContext->getGetParams();

        if ($isToEscape) {
            $model = $this->escapeAll($model);
        }

        if (empty($templateName)) {
            $templateName = $controller . DIRECTORY_SEPARATOR . $action;
        }

        if ($withHeader) {
            include self::VIEWS_FOLDER
                . DIRECTORY_SEPARATOR
                . self::PARTIALS_FOLDER
                . DIRECTORY_SEPARATOR
                . self::HEADER_NAME
                . self::VIEW_EXTENSION;
        }

        if ($isMessage) {
            include self::VIEWS_FOLDER
                . DIRECTORY_SEPARATOR
                . self::PARTIALS_FOLDER
                . DIRECTORY_SEPARATOR
                . self::MESSAGE_NAME
                . self::VIEW_EXTENSION;
        }

        include self::VIEWS_FOLDER
            . DIRECTORY_SEPARATOR
            . $templateName
            . self::VIEW_EXTENSION;

        if ($withFooter) {
            include self::VIEWS_FOLDER
                . DIRECTORY_SEPARATOR
                . self::PARTIALS_FOLDER
                . DIRECTORY_SEPARATOR
                . self::FOOTER_NAME
                . self::VIEW_EXTENSION;
        }
    }

    public function uri($controller, $action, $params = [], $getParams = [])
    {
        $url = $this->mvcContext->getUriJunk()
            . $controller
            . '/'
            . $action;

        if (! empty($params)) {
            $url .= '/' . implode('/', $params);
        }

        if (! empty($getParams)) {
            $url .= '?' . http_build_query($getParams, '' ,'&');
        }

        return $url;
    }

    public function generateUriWithOrderParams($fieldName, $filter = array())
    {
        $filter['filter']['page'] = 0;
        if (! isset($filter['filter'], $filter['filter']['order'], $filter['filter']['order'][$fieldName]) || strtoupper($filter['filter']['order'][$fieldName]) != 'ASC') {
            $orderDest = 'ASC';
        } else {
            $orderDest = 'DESC';
        }
        $filter['filter']['order'] = array(
            $fieldName => $orderDest
        );

        return $this->uri($this->mvcContext->getController(), $this->mvcContext->getAction(), $this->mvcContext->getArguments()) . '?' . http_build_query($filter);
    }

    public function generatePageUrl($page, $filter = array())
    {
        $filter['filter']['page'] = $page;

        return $this->uri($this->mvcContext->getController(), $this->mvcContext->getAction(), $this->mvcContext->getArguments()) . '?' . http_build_query($filter);
    }

    public function generatePageUrlCounter($onPage, $filter = array())
    {
        $filter['filter']['page'] = 1;
        $filter['filter']['onPage'] = $onPage;

        return $this->uri($this->mvcContext->getController(), $this->mvcContext->getAction(), $this->mvcContext->getArguments()) . '?' . http_build_query($filter);
    }

    public function urlSearch($filter = array())
    {
        unset($filter['filter']['search']);

        return $this->generatePageUrl(1, $filter);
    }

    private function escapeAll(&$toEscape){
        if (is_array($toEscape)) {
            foreach ($toEscape as $key => &$value) {
                if (is_object($value)) {
                    $reflection = new \ReflectionClass($value);
                    $properties = $reflection->getProperties();

                    foreach($properties as &$property){
                        $property->setAccessible(true);
                        $valueForEscape = $property->getValue($value);
                        $property->setValue($value, $this->escapeAll($valueForEscape));
                    }

                } else if (is_array($value)) {
                    $this->escapeAll($value);
                } else {
                    $value = htmlspecialchars($value);
                }

            }
        } else if (is_object($toEscape)) {
            $reflection = new \ReflectionClass($toEscape);
            $properties = $reflection->getProperties();

            foreach ($properties as &$property) {
                $property->setAccessible(true);
                $valueForEscape = $property->getValue($toEscape);
                $property->setValue($toEscape, $this->escapeAll($valueForEscape));
            }
        } else {
            $toEscape = htmlspecialchars($toEscape);
        }

        return $toEscape;
    }
}