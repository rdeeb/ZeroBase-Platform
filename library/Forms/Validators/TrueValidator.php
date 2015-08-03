<?php
namespace Zerobase\Forms\Validators;

class TrueValidator extends AbstractValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'true' => __( 'This field needs to be true', 'zerobase' )
        );
    }

    public function assert()
    {
        if ( $this->value !== TRUE )
        {
            $this->resultErrors[] = $this->errorMessages[ 'true' ];

            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}
