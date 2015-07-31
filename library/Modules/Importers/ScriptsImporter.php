<?php
namespace Zerobase\Modules\Importers;

use Zerobase\Cache\FileCache;
use Zerobase\Skeletons\SkeletonLoader;

class ScriptsImporter extends AbstractImporter
{
    protected $allowed_keys = array(
      'path',
      'dependencies',
      'version',
      'in_footer',
      'admin'
    );

    public static function load( $key, array $config )
    {
        $admin_scripts = array();
        foreach ( $config as $script_name => $script_config ) {
            self::validate( $script_config );
            if ( isset( $script_config[ 'admin' ] ) && $script_config[ 'admin' ] )
            {
                $admin_scripts[ $script_name ] = self::sanitizeConfig( $key, $script_config );
                unset( $config[ $script_name ] );
            }
            else
            {
                $config[ $script_name ] = self::sanitizeConfig( $key, $script_config );
            }
        }

        $cache_enabled = (bool) get_option( 'zerobase_platform_cache', TRUE );
        $cache_bag = FileCache::getInstance()->createCache( 'scripts' );
        if (  !$cache_bag->has( $key ) || !$cache_enabled )
        {
            //If we reached this location, is because the cache was empty or compromised
            $scripts_code = SkeletonLoader::load( 'scripts', array(
                'key' => basename($key, ".yml"),
                'scripts' => $config,
                'admin' => $admin_scripts
            ));
            $cache_bag->store( basename($key, ".yml"), $scripts_code );
        }

        $cache_bag->retreive( $key );
    }

    private static function sanitizeConfig( $filename, array $config ) {
        $default = array(
          'dependencies'    => array(),
          'version'         => null,
          'in_footer'       => true
        );
        $config[ 'path' ] = plugin_dir_path( $filename ) . $config[ 'path' ];
        return array_merge( $config, $default );
    }
}
