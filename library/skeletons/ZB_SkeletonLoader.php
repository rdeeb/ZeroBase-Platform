<?php

class ZB_SkeletonLoader
{
    static public function load($skeleton, $variables)
    {
        $filename = ZEROBASE_LIBRARY_DIR . '/skeletons/' . $skeleton . '.skeleton.php';
        if ( file_exists( $filename ) )
        {
            extract( $variables );
            ob_start();
            include( $filename );
            $data = ob_get_contents();
            ob_end_clean();
            return $data;
        }
        throw new Exception("Skeleton $skeleton was not found.");
    }
}
