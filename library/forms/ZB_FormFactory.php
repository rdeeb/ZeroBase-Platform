<?php

class ZB_FormFactory
{
    /**
     * @param string $formName
     * @param string $renderer
     * @return ZB_Form
     */
    public static function createForm($formName, $renderer = null)
    {
        if (!$renderer)
        {
            $renderer = 'default';
        }
        $class = self::getRenderer($renderer);
        return new ZB_Form($formName, $class);
    }

    private static function getRenderer($renderer)
    {
        if (!self::checkIfRenderingClassExists($renderer))
        {
            self::loadRendererClass($renderer);
        }
        $className = self::getClassName($renderer);
        return new $className();
    }

    private static function checkIfRenderingClassExists($renderer)
    {
        return class_exists(self::getClassName($renderer));
    }

    private static function loadRendererClass($renderer)
    {
        $className = self::getClassName($renderer);
        $fileName = __DIR__.'/renderers/'.$className.'.php';
        if (file_exists($fileName))
        {
            require_once($fileName);
            if (!self::checkIfRenderingClassExists($renderer))
            {
                throw new Exception("A class with the name \"$className\" wasn't found in \"$fileName\"");
            }
        }
        else
        {
            throw new Exception("The file \"$fileName\" wasn't found");
        }
    }

    private function getClassName($string)
    {
        return 'ZB_'.ucwords($string).'Renderer';
    }
}