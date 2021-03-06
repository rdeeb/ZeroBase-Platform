<?php
namespace Zerobase\Forms\Validators;

abstract class AbstractValidator implements ValidatorInterface
{
    protected $value;
    protected $errorMessages = array();
    protected $options = array();
    protected $resultErrors = array();

    public function __construct(array $options = array(), array $errorMessages = array())
    {
        if ( !empty( $options ) )
        {
            $this->setConfiguration($options);
            $this->setErrorMessages($errorMessages);
        }
    }

    protected function setErrorMessages(array $errorMessages)
    {
        $this->errorMessages = $this->getDefaultMessages();
        if (!empty($errorMessages))
        {
            foreach($errorMessages as $error => $message)
            {
                $this->errorMessages[$error] = $message;
            }
        }
    }

    protected function setConfiguration(array $options)
    {
        $this->options = $this->getDefaultOptions();
        if (!empty($options))
        {
            foreach($options as $option => $value)
            {
                $this->options[$option] = $value;
            }
        }
    }

    protected function getDefaultMessages()
    {
        return array();
    }

    protected function getDefaultOptions()
    {
        return array();
    }

    public function setValue( $value )
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->resultErrors;
    }
}
