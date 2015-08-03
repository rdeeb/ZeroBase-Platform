<?php
namespace Zerobase\Forms\Validators;

class FalseValidator extends AbstractValidator
{
    protected function getDefaultMessages()
    {
        return array(
            'false' => __( 'This field needs to be false', 'zerobase' )
        );
    }

    public function assert()
    {
        if ( $this->value !== FALSE )
        {
            $this->resultErrors[] = $this->errorMessages[ 'false' ];

            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}
