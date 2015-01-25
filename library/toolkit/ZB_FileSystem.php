<?php

class ZB_FileSystem
{
    public static function fileLoad( $class_name, $filepath )
    {
        if ( class_exists( $class_name ) )
        {
            return false;
        }
        if ( is_readable( $filepath.$class_name.'.php' ) )
        {
            require_once( $filepath.$class_name.'.php' );
            return true;
        }
        else
        {
            return false;
        }
    }

    public function loadDir( $path, $pattern )
    {
        if ( !is_dir( $path ) )
        {
            return false;
        }

        $files = readdir( $path );
    }
}
