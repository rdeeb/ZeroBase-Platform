<?php
namespace Zerobase\Forms\Validators;

class EmailValidator extends AbstractValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'invalid' => __( "This is an invalid email address", 'zerobase' )
        );
    }

    public function assert()
    {
        if ( empty( $this->value ) && filter_var( $this->value, FILTER_VALIDATE_EMAIL ) )
        {
            $this->resultErrors[] = $this->errorMessages[ 'invalid' ];

            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}
