<?php
namespace Zerobase\Modules\Importers;

use Zerobase\Cache\FileCache;
use Zerobase\Skeletons\SkeletonLoader;
use Zerobase\Taxonomy\TaxonomyExtender;

class TaxonomyImporter extends AbstractImporter
{
    protected $allowed_keys = array(
      'label',
      'labels',
      'name',
      'singular_name',
      'menu_name',
      'all_items',
      'edit_item',
      'view_item',
      'update_item',
      'add_new_item',
      'new_item_name',
      'parent_item_colon',
      'search_items',
      'popular_items',
      'separate_items_with_commas',
      'add_or_remove_items',
      'choose_from_most_used',
      'not_found',
      'public',
      'show_ui',
      'show_in_nav_menus',
      'show_tagcloud',
      'show_in_quick_edit',
      'meta_box_cb',
      'show_admin_column',
      'hierarchical',
      'update_count_callback',
      'query_var',
      'rewrite',
      'slug',
      'with_front',
      'hierarchical',
      'ep_mask',
      'capabilities',
      'manage_terms',
      'edit_terms',
      'delete_terms',
      'assign_terms',
      'sort',
      'fields',
      'post_type'
    );

    public static function load( $key, array $config )
    {
        self::validate( $config );
        $config = self::sanitizeConfig( $config );
        if ( !isset( $config['post_type'] ) )
        {
            throw new \Exception('You need to specify the post types that this taxonomy will attach to');
        }
        $arguments = self::getArguments( $config );
        $fields = array();
        if ( isset( $config[ 'fields' ] ) )
        {
            $fields = $config[ 'fields' ];
        }
        $cache_enabled = (bool) get_option( 'zerobase_platform_cache', TRUE );
        if ( $cache_enabled )
        {
            //If we reached this location, is because the cache was empty or compromised
            $cache_bag = FileCache::getInstance()->createCache( 'taxonomies' );
            $post_type_code = SkeletonLoader::load( 'taxonomy', array(
              'args' => $arguments,
              'taxonomy_name' => $key,
              'attach_to' => $config['post_type'],
              'fields' => $fields
            ));
            $cache_bag->store( $key, $post_type_code );
        }
        register_taxonomy( $key, $config['post_type'], $arguments );
        if ( !empty( $fields ) )
        {
            $tax_extender = new TaxonomyExtender( $key, $fields );
            $tax_extender->register();
        }
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
              'search_items' => 'Search Items',
              'not_found' => 'No items where found',
              'not_found_in_trash' => 'No items where found in the trash bin',
            ),
          'arguments' =>
            array (
              'public' => true,
            )
        );
        return array_merge( $default, $config );
    }

    private static function getArguments($config)
    {
        $arguments = $config[ 'arguments' ];
        //If the labels array is outside of arguments, copy it inside of arguments
        if ( isset( $config[ 'labels' ] ) )
        {
            $arguments[ 'labels' ] = $config[ 'labels' ];
        }
        return $arguments;
    }
}
