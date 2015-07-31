<?php
echo __DIR__."/n";
spl_autoload_register( function ( $class ) {
    if ( stripos( $class, 'zerobase' ) !== FALSE )
    {
        ZB_Autoloader::autoloadDir($class, 'Zerobase\\', __DIR__ . '/library/');
    }
    else if ( stripos( $class, 'yaml' ) !== FALSE )
    {
        ZB_Autoloader::autoloadDir($class, 'Symfony\\Component\\', __DIR__ . '/vendor/symfony/');
    }
    else if ( stripos( $class, 'wp_mock' ) !== FALSE )
    {
        require_once __DIR__ . '/vendor/10up/WP_Mock.php';
        ZB_Autoloader::autoloadDir($class, 'WP_Mock\\', __DIR__ . '/vendor/10up/WP_Mock/');
    }
    else if ( stripos( $class, 'mockery' ) !== FALSE )
    {
        require_once __DIR__ . '/vendor/mockery/library/Mockery.php';
        ZB_Autoloader::autoloadDir($class, 'Mockery\\', __DIR__ . '/vendor/mockery/library/Mockery/');
    }
});

class ZB_Autoloader
{
    public static function autoloadDir($class, $prefix, $base_dir)
    {
        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relative_class = substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    }
}
