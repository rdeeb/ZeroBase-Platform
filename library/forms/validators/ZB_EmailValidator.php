<?php
require_once(__DIR__.'/ZB_BaseValidator.php');

class ZB_EmailValidator extends ZB_BaseValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'invalid' => __("This is an invalid email address", 'zerobase')
        );
    }

    public function assert()
    {
        if (empty($this->value) && filter_var($this->value, FILTER_VALIDATE_EMAIL))
        {
            $this->resultErrors[] = $this->errorMessages['invalid'];
            return false;
        }
        else
        {
            return true;
        }
    }
}
