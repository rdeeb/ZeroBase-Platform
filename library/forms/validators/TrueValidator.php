<?php
namespace Zerobase\Forms\Validators;

class TrueValidator extends AbstractValidator
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
