<?php
namespace Zerobase\Forms\Models;

use Zerobase\Toolkit\Request;

class MetadataModel extends AbstractModel
{

    protected function getPostId()
    {
        global $post;
        $request = Request::getInstance();
        if ( !empty( $post ) && $post->ID )
        {
            return $post->ID;
        }
        else if ( $request->has( 'post' ) )
        {
            return $request->get( 'post' );
        }
        else if ( $request->has( 'post_ID' ) )
        {
            return $request->get( 'post_ID' );
        }
    }

    protected function storeData( $name, $value )
    {
        update_post_meta( $this->getPostId(), $name, $value );
    }

    protected function retreiveData( $name, $default )
    {
        $value = get_post_meta( $this->getPostId(), $name, TRUE );

        return empty( $value ) ? $default : $value;
    }
}
