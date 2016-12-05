<?php
/**
 * Created by PhpStorm.
 * User: Popov
 * Date: 4.12.2016 г.
 * Time: 20:43
 */

namespace FPopov\Core\MVC;


class Message
{
    const POSITIVE_MESSAGE = 'positive';
    const NEGATIVE_MESSAGE = 'negative';

    private static $instance;

    private $container = [];

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->container[self::POSITIVE_MESSAGE] = [];
        $this->container[self::NEGATIVE_MESSAGE] = [];

        $this->session = $session;

        $messages = $session->exists('message') ? $session->get('message') : '';
        if (! empty($messages)) {
            $this->container = array_merge($this->container, $messages);
            $session->remove('message');
        }
    }

    private static function getInstance()
    {
        $session = new Session($_SESSION);
        if (! self::$instance) {
            self::$instance = new Message($session);
        }
        return self::$instance;
    }

    private static function set($text, $type)
    {
        $instance = self::getInstance();
        $instance->container[$type][] = $text;
    }

    public static function setError($text)
    {
        self::set($text, self::NEGATIVE_MESSAGE);
    }

    public static function setPositive($text)
    {
        self::set($text, Message::POSITIVE_MESSAGE);
    }

    public static function postMessage($text, $type)
    {
        $session = new Session($_SESSION);
        $messages = $session->exists('message') ? $session->get('message') : [];
        $messages[$type][] = $text;

        $session->set('message', $messages);
    }

    public static function returnMessages()
    {
        $instance = self::getInstance();
        $messages = [];

        foreach($instance->container as $type => $messagesPool) {
            if (empty($messagesPool)) {
                continue;
            }

            foreach ($messagesPool as $code => $text) {
                $messages[] = (object) [
                    'type' => $type,
                    'text' => $text
                ];
            }
        }
        return $messages;
    }
}