<?php
/**
 * Plugin Name: ZeroBase Platform
 * Plugin URI: https://github.com/rdeeb/ZeroBase-Platform
 * Description: This is the base of the ZeroBase Wordpress Framework.
 * Version: 0.3
 * Author: Ramy Deeb
 * Author URI: http://www.ramydeeb.com
 * License: Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 *
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 */

class zerobase_platform
{
    private $post_type_path;
    private $post_types;

    /**
     * __construct Initializes the platform
     *
     * @return void
     * @author Ramy Deeb
     */
    public function __construct()
    {
        //Load the toolkit
        require_once( __DIR__ . '/toolkit/zerobase_html_toolkit.php' );
        //Load the Form Builder
        require_once( __DIR__ . '/forms/zerobase_form_builder.php' );
        require_once( __DIR__ . '/forms/zerobase_tax_form_builder.php' );
        require_once( __DIR__ . '/forms/zerobase_widget_form_builder.php' );
        //Load the Metabox Builder
        require_once( __DIR__ . '/metaboxes/zerobase_metabox.php' );
        //Load the post type interface and base class
        require_once( __DIR__ . '/post-types/zerobase_post_type_interface.php' );
        require_once( __DIR__ . '/post-types/zerobase_base_post_type.php' );
        //Load the taxonomy extender class
        require_once( __DIR__ . '/taxonomies/zerobase_taxonomy_extender.php' );
        //Load the widget base class
        require_once( __DIR__ . '/widgets/zerobase_base_widget.php' );
        //Extend the database
        require_once( __DIR__ . '/installation/zerobase_create_tables.php' );
        global $wpdb;
        $type = 'zerobase_term';
        $table_name = $wpdb->prefix . $type . 'meta';
        $variable_name = $type . 'meta';
        $wpdb->$variable_name = $table_name;
        zerobase_create_metadata_table( $table_name, $type );
        //Register the framework scripts and styles
        add_action( 'wp_register_scripts', array( &$this, 'registerScripts' ) );
    }

    /**
     * setPostTypePath Stores the path were the post type classes are stored
     *
     * @param $path string The path were the system should load the post types
     *
     * @throws Exception
     * @return void
     * @author Ramy Deeb
     */
    public function setPostTypePath( $path )
    {
        if ( is_dir( $path ) )
        {
            $this->post_type_path = $path;
        }
        else
        {
            throw new Exception( "'$path' is not a valid directory" );
        }
    }

    /**
     * addPostType Loads a new post type class into the platform
     *
     * @param $class string The class name to add
     *
     * @throws Exception
     * @return void
     * @author Ramy Deeb
     */
    public function addPostType( $class )
    {
        if ( !$this->post_type_path )
        {
            throw new Exception( 'Before adding post types, you need first to add a path' );
        }
        $tainted_file = $this->post_type_path . '/' . $class . '.php';
        if ( !file_exists( $tainted_file ) )
        {
            throw new Exception( "A file named '$class.php' couldn't be found at '{$this->post_type_path}'" );
        }
        require_once( $tainted_file );
        if ( !is_array( $this->post_types ) )
        {
            $this->post_types = array(
                $class => new $class()
            );
        }
        else
        {
            $this->post_types[$class] = new $class();
        }
    }

    /**
     * getPostType Returns the instance of a post type
     *
     * @param $class string The class name to return
     *
     * @return mixed
     * @throws Exception
     * @author Ramy Deeb
     */
    public function getPostType( $class )
    {
        if ( array_key_exists( $class, $this->post_types ) )
        {
            return $this->post_types[$class];
        }
        else
        {
            throw new Exception( "The post type '$class' has not been loaded" );
        }
    }

    /**
     * registerScripts Registers the framework scripts
     *
     * @return void
     * @author Ramy Deeb
     */
    public function registerScripts()
    {
        //Register the color picker script & styles
        wp_register_script(
            'zerobase_js_colorpicker',
            __DIR__ . '/forms/js/colorpicker.min.js',
            array(
                'jquery'
            ),
            NULL,
            true
        );
        wp_register_style(
            'zerobase_css_colorpicker',
            __DIR__ . '/forms/css/colorpicker.css'
        );
        wp_register_script(
            'zerobase_js_forms',
            __DIR__ . '/forms/js/forms.min.js',
            array(
                'jquery',
                'jquery-ui-core',
                'jquery-ui-widget',
                'jquery-ui-datepicker',
                'media-upload',
                'thickbox'
            ),
            NULL,
            true
        );
        wp_localize_script( 'zerobase_js_forms', 'forms_trans', array(
            'gallery_title'  => __( 'Select the images for the gallery', 'zerobase' ),
            'gallery_submit' => __( 'Choose gallery', 'zerobase' ),
            'image_title'    => __( 'Select an image', 'zerobase' ),
            'image_submit'   => __( 'Choose image', 'zerobase' ),
            'file_title'     => __( 'Select a file', 'zerobase' ),
            'file_submit'    => __( 'Choose file', 'zerobase' ),
        ) );
        wp_register_style(
            'zerobase_css_forms',
            __DIR__ . '/forms/css/forms.css',
            array(
                'thickbox'
            )
        );
    }
}