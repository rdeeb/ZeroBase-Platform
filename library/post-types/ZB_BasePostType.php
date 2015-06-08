<?php
/**
 * ZB_BasePostType
 *
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 */

class ZB_BasePostType
{
    protected $name;
    protected $config;

    public function __construct( $name, array $config ) {
        $this->name = $name;
        $this->config = $this->sanitizeConfig( $config );
    }

    private function sanitizeConfig( array $config ) {
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
              'not_found_in_trash' => 'No items where foind in the trash bin',
              'parent_item_colon' => ':',
            ),
          'arguments' =>
            array (
              'public' => true,
              'menu_position' => 5,
            )
        );
        return array_merge( $default, $config );
    }

    public function register() {
        $cache = ZB_FileCache::getInstance();
        $cache_bag = $cache->createCache('post_types');
        $post_type_eval = $cache_bag->retreive($this->name);
        if ( $post_type_eval == false )
        {
            $arguments = $this->config['arguments'];
            $arguments['labels'] = $this->config['labels'];
            $post_type_eval = ZB_SkeletonLoader::load('post', array(
              'args' => $arguments,
              'post_type_name' => $this->name
            ));
            $cache_bag->store( $this->name, $post_type_eval );
        }
        //register_post_type($this->name, $arguments);
        eval($post_type_eval);
    }
}
