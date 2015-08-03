<?php
namespace Zerobase\Forms\Models;

class OptionsModel extends AbstractModel
{
    protected function storeData( $name, $value )
    {
        update_option( $name, $value, FALSE );
    }

    protected function retreiveData( $name, $default )
    {
        return get_option( $name, $default );
    }
}
