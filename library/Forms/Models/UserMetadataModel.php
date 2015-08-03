<?php
namespace Zerobase\Forms\Models;

class UserMetadataModel extends AbstractModel
{

    protected function storeData( $name, $value )
    {
        update_user_meta( get_current_user_id(), $name, $value );
    }

    protected function retreiveData( $name, $default )
    {
        $value = get_the_author_meta( $name, get_current_user_id() );

        return empty( $value ) ? $default : $value;
    }
}
