<?php
require_once(__DIR__.'/ZB_AbstractValidator.php');

class ZB_RequiredValidator extends ZB_AbstractValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'required' => __("This field can't be left blank", 'zerobase')
        );
    }

    public function assert()
    {
        if (empty($this->value))
        {
            $this->resultErrors[] = $this->errorMessages['required'];
            return false;
        }
        else
        {
            return true;
        }
    }
}
