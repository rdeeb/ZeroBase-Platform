<?php
require_once(__DIR__.'/ZB_BaseValidator.php');

class ZB_TrueValidator extends ZB_BaseValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'true' => __('This field needs to be true', 'zerobase')
        );
    }

    public function assert()
    {
        if ($this->value !== true)
        {
            $this->resultErrors[] = $this->errorMessages['true'];
            return false;
        }
        else
        {
            return true;
        }
    }
}