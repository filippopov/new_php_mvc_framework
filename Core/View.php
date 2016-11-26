<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 25.11.2016 г.
 * Time: 14:02
 */

namespace FPopov\Core;


class View
{
    const VIEWS_FOLDER = 'views';
    const PARTIALS_FOLDER = 'partials';
    const HEADER_NAME = 'header';
    const FOOTER_NAME = 'footer';
    const STATIC_EXTENSION = '.html';
    const VIEW_EXTENSION = '.php';

    public function render($templateName, $model = null)
    {
        include self::VIEWS_FOLDER
            . DIRECTORY_SEPARATOR
            . self::PARTIALS_FOLDER
            . DIRECTORY_SEPARATOR
            . self::HEADER_NAME
            . self::STATIC_EXTENSION;


        include self::VIEWS_FOLDER
            . DIRECTORY_SEPARATOR
            . $templateName
            . self::VIEW_EXTENSION;

        include self::VIEWS_FOLDER
            . DIRECTORY_SEPARATOR
            . self::PARTIALS_FOLDER
            . DIRECTORY_SEPARATOR
            . self::FOOTER_NAME
            . self::STATIC_EXTENSION;
    }
}