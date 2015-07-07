<?php
namespace Zerobase\Forms\Validators;

use Zerobase\Toolkit\Singleton;

class ValidatorFactory extends Singleton
{

    private $validators = array();

    protected function __construct()
    {
        $this->loadDefaultValidators();
    }

    private function loadDefaultValidators()
    {
        $this->addWidgetValidator('required', 'RequiredValidator' );
        $this->addWidgetValidator('email', 'EmailValidator' );
        $this->addWidgetValidator('false', 'FalseValidator' );
        $this->addWidgetValidator('length', 'LengthValidator' );
        $this->addWidgetValidator('range', 'RangeValidator' );
        $this->addWidgetValidator('true', 'TrueValidator' );
    }

    /**
     * @param string $name
     * @param string $className
     * @throws \Exception
     */
    public function addWidgetValidator($name, $className)
    {
        if (!$this->checkClassImplements($className))
        {
            throw new \Exception("The class \"$className\" must implement the ValidatorInterface");
        }
        else
        {
            $this->validators[$name] = $className;
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
     * @throws \Exception
     * @return ValidatorInterface
     */
    public function createValidator($name, $options)
    {
        if ($this->validatorExists($name))
        {
            $className = 'Zerobase\Forms\Validators\\' . $this->validators[$name];
            return new $className($options['options'], $options['messages']);
        }
        else
        {
            throw new \Exception("The validator \"$name\" doesn't exists");
        }
    }

    /**
     * @param string $className
     * @return bool
     * @throws \Exception
     */
    private function checkClassImplements($className)
    {
        $class = 'Zerobase\Forms\Validators\\' . $className;
        try
        {
            $obj = new $class();
            unset($obj);
        }
        catch(\Exception $e)
        {
            throw new \Exception("The class \"$className\" doesn't exists");
        }
        $implements = class_implements($class);
        if (in_array('Zerobase\Forms\Validators\ValidatorInterface', $implements))
        {
            return true;
        }
        return false;
    }
}
