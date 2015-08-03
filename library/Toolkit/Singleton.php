<?php
namespace Zerobase\Toolkit;

abstract class Singleton
{
    protected function __construct()
    {
    }

    /**
     * @return static
     */
    static function getInstance()
    {
        static $instance = NULL;
        if ( NULL === $instance )
        {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * This functions prevents the cloning of the instance
     */
    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
