<?php
require_once(__DIR__.'/ZB_BaseValidator.php');

class ZB_FalsedValidator extends ZB_BaseValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'false' => __('This field needs to be false', 'zerobase')
        );
    }

    public function assert()
    {
        if ($this->value !== false)
        {
            $this->resultErrors[] = $this->errorMessages['false'];
            return false;
        }
        else
        {
            return true;
        }
    }
}