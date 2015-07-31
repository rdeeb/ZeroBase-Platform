<?php
namespace Zerobase\Modules\Importers;

use Symfony\Component\Yaml\Exception\ParseException;
use Zerobase\Cache\FileCache;
use Zerobase\Skeletons\SkeletonLoader;

class WidgetImporter extends AbstractImporter
{
    protected $allowed_keys = array(
      'name',
      'description',
      'fields',
      'type',
      'default',
      'label',
      'choices',
      'template',
    );

    public static function load( $key, array $config )
    {
        self::validate( $config );
        $config = self::sanitizeConfig( $config );
        if ( !class_exists( $key ) )
        {
            $cache_enabled = (bool) get_option( 'zerobase_platform_cache', TRUE );
            $cache_bag = FileCache::getInstance()->createCache( 'widgets' );
            if (  !$cache_bag->has( $key ) || !$cache_enabled )
            {
                $widget_code = SkeletonLoader::load( 'widget', array(
                  'class_name' => $key,
                  'name' => $config[ 'name' ],
                  'description' => $config[ 'description' ],
                  'fields' => $config[ 'fields' ],
                  'template' => $config[ 'base_path' ] . '/' . $config[ 'template' ]
                ));
                $cache_bag->store( $key, $widget_code );
            }

            $cache_bag->retreive( $key );
        }

    }

    private static function sanitizeConfig( array $config ) {
        if ( !isset( $config['template'] ) ) {
            throw new ParseException('The template dir is required');
        }
        return $config;
    }
}
