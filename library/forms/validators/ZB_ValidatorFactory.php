<?php

class ZB_ValidatorFactory extends ZB_Singleton
{

    private $validators = array();

    protected function __construct()
    {
        $this->loadDefaultValidators();
    }

    private function loadDefaultValidators()
    {
        $this->addWidgetValidator('required', 'ZB_RequiredValidator', __DIR__ . '/ZB_RequiredValidator.php' );
        $this->addWidgetValidator('email', 'ZB_EmailValidator', __DIR__ . '/ZB_EmailValidator.php' );
        $this->addWidgetValidator('false', 'ZB_FalseValidator', __DIR__ . '/ZB_FalseValidator.php' );
        $this->addWidgetValidator('length', 'ZB_LengthValidator', __DIR__ . '/ZB_LengthValidator.php' );
        $this->addWidgetValidator('range', 'ZB_RangeValidator', __DIR__ . '/ZB_RangeValidator.php' );
        $this->addWidgetValidator('true', 'ZB_TrueValidator', __DIR__ . '/ZB_TrueValidator.php' );
    }

    /**
     * @param string $name
     * @param string $className
     * @param string $filePath
     * @throws Exception
     */
    public function addWidgetValidator($name, $className, $filePath)
    {
        if (file_exists($filePath))
        {
            require_once($filePath);
            if (!$this->checkClassImplements($className))
            {
                throw new Exception("The class \"$className\" must implement the ZB_ValidatorInterface");
            }
            else
            {
                $this->validators[$name] = $className;
            }
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function validatorExists($name)
    {
        return array_key_exists($name, $this->validators);
    }

    /**
     * @param string $name
     * @param array $options
     * @throws Exception
     * @return ZB_ValidatorInterface
     */
    public function createValidator($name, $options)
    {
        if ($this->validatorExists($name))
        {
            $className = $this->validators[$name];
            return new $className($options['options'], $options['messages']);
        }
        else
        {
            throw new Exception("The validator \"$name\" doesn't exists");
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
        if (in_array('ZB_ValidatorInterface', $implements))
        {
            return true;
        }
        return false;
    }
}
