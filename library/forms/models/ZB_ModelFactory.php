<?php

class ZB_ModelFactory extends ZB_Singleton
{

    private $models = array();

    protected function __construct()
    {
        $this->loadDefaultModels();
    }

    private function loadDefaultModels()
    {
        $this->addWidgetModel('memory', 'ZB_MemoryModel', __DIR__ . '/ZB_MemoryModel.php' );
        $this->addWidgetModel('metadata', 'ZB_MetadataModel', __DIR__ . '/ZB_MetadataModel.php' );
        $this->addWidgetModel('option', 'ZB_OptionsModel', __DIR__ . '/ZB_OptionsModel.php' );
    }

    /**
     * @param string $name
     * @param string $className
     * @param string $filePath
     * @throws Exception
     */
    public function addWidgetModel($name, $className, $filePath)
    {
        if (file_exists($filePath))
        {
            require_once($filePath);
            if (!$this->checkClassImplements($className))
            {
                throw new Exception("The class \"$className\" must implement the ZB_ModelInterface");
            }
            else
            {
                $this->models[$name] = $className;
            }
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function ModelExists($name)
    {
        return array_key_exists($name, $this->models);
    }

    /**
     * @param string $name
     * @param array $options
     * @throws Exception
     * @return ZB_ModelInterface
     */
    public function createModel($name)
    {
        if ($this->ModelExists($name))
        {
            $className = $this->models[$name];
            return new $className();
        }
        else
        {
            throw new Exception("The Model \"$name\" doesn't exists");
        }
    }

    /**
     * @param string $className
     * @return bool
     * @throws Exception
     */
    private function checkClassImplements($className)
    {
        if (!class_exists($className))
        {
            throw new Exception("The class \"$className\" doesn't exists");
        }
        $implements = class_implements($className);
        if (in_array('ZB_ModelInterface', $implements))
        {
            return true;
        }
        return false;
    }
}