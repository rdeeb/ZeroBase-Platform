<?php
namespace Zerobase\Skeletons;

class SkeletonLoader
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
            return "<?php $data ?>";
        }
        throw new Exception("Skeleton $skeleton was not found.");
    }
}
