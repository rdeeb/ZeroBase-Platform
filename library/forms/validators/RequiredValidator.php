<?php
namespace Zerobase\Forms\Validators;

class RequiredValidator extends AbstractValidator
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
