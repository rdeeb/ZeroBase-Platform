<?php
namespace Zerobase\Modules\Importers;

use Zerobase\Cache\FileCache;
use Zerobase\Skeletons\SkeletonLoader;

class PostTypeImporter extends AbstractImporter
{
    protected $allowed_keys = array(
      'labels',
      'arguments',
      'name',
      'singular_name',
      'menu_name',
      'all_items',
      'add_new',
      'add_new_item',
      'edit_item',
      'new_item',
      'view_item',
      'search_items',
      'not_found',
      'not_found_in_trash',
      'parent_item_colon',
      'label',
      'description',
      'public',
      'exclude_from_search',
      'publicly_queryable',
      'show_ui',
      'show_in_nav_menus',
      'show_in_menu',
      'show_in_admin_bar',
      'menu_position',
      'menu_icon',
      'capability_type',
      'capabilities',
      'edit_post',
      'edit_posts',
      'edit_others_posts',
      'publish_posts',
      'read_private_posts',
      'read_post',
      'delete_post',
      'map_meta_cap',
      'hierarchical',
      'supports',
      'taxonomies',
      'has_archive',
      'permalink_epmask',
      'rewrite',
      'slug',
      'with_front',
      'feeds',
      'pages',
      'ep_mask',
      'query_var',
      'can_export',

    );

    public static function load( $key, array $config )
    {
        self::validate( $config );
        $config = self::sanitizeConfig( $config );
        $arguments = $config[ 'arguments' ];
        //If the labels array is outside of arguments, copy it inside of arguments
        if ( isset( $config[ 'labels' ] ) )
        {
            $arguments[ 'labels' ] = $config[ 'labels' ];
        }
        $cache_enabled = (bool) get_option( 'zerobase_platform_cache', TRUE );
        if ( $cache_enabled )
        {
            //If we reached this location, is because the cache was empty or compromised
            $cache_bag = FileCache::getInstance()->createCache( 'post_types' );
            $post_type_code = SkeletonLoader::load( 'post', array(
              'args' => $arguments,
              'post_type_name' => $key
            ));
            $cache_bag->store( $key, $post_type_code );
        }
        register_post_type( $key, $arguments );
    }

    private static function sanitizeConfig( array $config ) {
        $default = array (
          'labels' =>
            array (
              'add_new' => 'Add New',
              'add_new_item' => 'Add New Item',
              'edit_item' => 'Edit Item',
              'new_item' => 'New Item',
              'all_items' => 'All Items',
              'view_item' => 'View Item',
              'search_items' => 'Search Items',
              'not_found' => 'No items where found',
              'not_found_in_trash' => 'No items where found in the trash bin',
            ),
          'arguments' =>
            array (
              'public' => true,
              'menu_position' => 5,
            )
        );
        return array_merge( $default, $config );
    }
}
