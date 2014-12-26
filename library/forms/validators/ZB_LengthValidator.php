<?php
require_once(__DIR__.'/ZB_AbstractValidator.php');

class ZB_LengthValidator extends ZB_AbstractValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'min' => sprintf(__("This field needs to be at least %d characters long", 'zerobase'), $this->options['min']),
            'max' => sprintf(__("This field needs to be less than %d characters long", 'zerobase'), $this->options['max'])
        );
    }

    public function assert()
    {
        if (isset($this->options['min']) && strlen($this->value) < $this->options['min'])
        {
            $this->resultErrors[] = $this->errorMessages['min'];
            return false;
        }
        else if (isset($this->options['max']) && strlen($this->value) > $this->options['max'])
        {
            $this->resultErrors[] = $this->errorMessages['min'];
            return false;
        }
        else
        {
            return true;
        }
    }
}