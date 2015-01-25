<?php

class ZB_FormFactory
{
    /**
     * @param string $formName
     * @param string $renderer
     * @return ZB_Form
     */
    public static function createForm($formName, $renderer = 'default', $model = 'memory')
    {
        $rendererClass = self::getRenderer($renderer);
        $modelClass = self::getModel($model);
        return new ZB_Form($formName, $rendererClass, $modelClass);
    }

    private static function getModel($model)
    {
        $modelFactory = ZB_ModelFactory::getInstance();
        return $modelFactory->createModel($model);
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

    private static function getClassName($string)
    {
        return 'ZB_'.ucwords($string).'Renderer';
    }
}
