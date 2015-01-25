<?php
require_once(__DIR__.'/ZB_AbstractValidator.php');

class ZB_RangeValidator extends ZB_AbstractValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'min' => sprintf(__("This field needs to be greater than %d", 'zerobase'), $this->options['min']),
            'max' => sprintf(__("This field needs to be less than %d", 'zerobase'), $this->options['max'])
        );
    }

    public function assert()
    {
        if (isset($this->options['min']) && $this->value < $this->options['min'])
        {
            $this->resultErrors[] = $this->errorMessages['min'];
            return false;
        }
        else if (isset($this->options['max']) && $this->value > $this->options['max'])
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
