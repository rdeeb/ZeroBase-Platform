<?php
namespace Zerobase\Modules\Importers;

use Zerobase\Cache\FileCache;
use Zerobase\Metaboxes\Metabox;
use Zerobase\Skeletons\SkeletonLoader;

class MetaboxImporter extends AbstractImporter
{
    protected $allowed_keys = array(
      'labels',
      'post_type',
      'context',
      'priority',
      'template',
      'fields',
      'label',
      'type',
      'icon',
      'prepend',
      'choices'
    );

    public static function load( $key, array $config )
    {
        self::validate( $config );
        $config = self::sanitizeConfig( $config );
        $config[ 'id' ] = $key;
        $cache_enabled = (bool) get_option( 'zerobase_platform_cache', TRUE );
        if ( $cache_enabled )
        {
            //If we reached this location, is because the cache was empty or compromised
            $cache_bag = FileCache::getInstance()->createCache( 'metaboxes' );
            $metabox_code = SkeletonLoader::load( 'metabox', array(
              'config' => $config,
            ));
            $cache_bag->store( $key, $metabox_code );
        }
        new Metabox( $config );
    }

    private static function sanitizeConfig( array $config ) {
        $default = array (
          'post_type' =>
            array (
              'post',
            ),
          'context' => 'normal',
          'priority' => 'normal'
        );
        return array_merge( $default, $config );
    }
}
