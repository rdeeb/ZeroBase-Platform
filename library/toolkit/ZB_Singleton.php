<?php

abstract class ZB_Singleton
{
    protected function __construct(){}
    /**
     * @return static
     */
    static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    /**
     * This functions prevents the cloning of the instance
     */
    private function __clone() {}
    private function __wakeup() {}
}