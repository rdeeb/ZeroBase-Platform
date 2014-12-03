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
    /**
     * __construct Initializes the platform
     *
     * @author Ramy Deeb
     */
    protected function __construct()
    {
        // Define the plugin base directory
        $dir = plugin_dir_path( __FILE__ );
        $lib_dir = $dir.'/library';
        //Load the toolkit
        require_once( $lib_dir . '/toolkit/zerobase_html_toolkit.php' );
        //Load the Form Builder
        require_once( $lib_dir . '/forms/zerobase_form_builder.php' );
        require_once( $lib_dir . '/forms/zerobase_tax_form_builder.php' );
        require_once( $lib_dir . '/forms/zerobase_widget_form_builder.php' );
        //Load the Metabox Builder
        require_once( $lib_dir . '/metaboxes/zerobase_metabox.php' );
        //Load the post type interface and base class
        require_once( $lib_dir . '/post-types/zerobase_post_type_interface.php' );
        require_once( $lib_dir . '/post-types/zerobase_base_post_type.php' );
        //Load the taxonomy extender class
        require_once( $lib_dir . '/taxonomies/zerobase_taxonomy_extender.php' );
        //Load the widget base class
        require_once( $lib_dir . '/widgets/zerobase_base_widget.php' );
        //Extend the database
        require_once( $dir . '/installation/zerobase_create_tables.php' );
        global $wpdb;
        $type = 'zerobase_term';
        $table_name = $wpdb->prefix . $type . 'meta';
        $variable_name = $type . 'meta';
        $wpdb->$variable_name = $table_name;
        zerobase_create_metadata_table( $table_name, $type );
        //Register the framework scripts and styles
        add_action( 'admin_enqueue_scripts', array( &$this, 'registerAdminScripts' ), 1 );
        $this->platform_options = get_option( 'zerobase_platoform_options', array() );
        if ( empty( $this->platform_options ) )
        {
            $this->setOption( 'version', '0.2' );
        }
        //echo '<p>Calling execute hooks</p>';
        add_action( 'after_setup_theme', array( &$this, 'executeHooks' ), 1 );
        add_action( 'after_setup_theme', array( &$this, 'loadModules' ), 2 );
        add_action( 'init', array( &$this, 'configurePostTypes' ), 10 );
        add_action( 'init', array( &$this, 'configureTaxonomies' ), 11 );
        add_action( 'save_post', array( &$this, 'saveMetaboxesData' ), 10, 2 );
    }

    /**
     * Returns the current instance of the Zerobase Platform
     * @return zerobase_platform
     */
    static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * This functions prevents the cloning of the instance
     */
    private function __clone() {}
    private function __wakeup() {}

    private function setOption( $key, $value )
    {
        $this->platform_options[ $key ] = $value;
        add_option( 'zerobase_platform_options', $this->platform_options );
    }

    private function getOption( $key, $default = null )
    {
        if ( isset( $this->platform_options[ $key ] ) )
        {
            return $this->platform_options[ $key ];
        }
        else
        {
            return $default;
        }
    }

    /**
     * addModule Adds a new module to the platform
     *
     * @param array $config defines de configuration parameters of the module being loaded
     *
     * @throws Exception
     * @author Ramy Deeb
     */
    public function addModule( array $config )
    {
        if (!isset($config[ 'path' ]) || empty($config[ 'path' ]) || !is_dir( $config[ 'path' ] ))
        {
            throw new Exception('Every module must define a valid path');
        }
        $modules = $this->getOption( 'modules', array() );
        if ( !isset( $config[ 'name' ] ) )
        {
            throw new Exception('Every module must define a name');
        }
        $modules[ $this->slugify( $config[ 'name' ] ) ] = $config;
        $this->setOption( 'modules', $modules );
        //echo '<p>Added module '.$config[ 'name' ].'</p>';
    }

    public function loadModules()
    {
        $modules = $this->getOption( 'modules', array() );
        $classes = $this->getOption( 'classes', array() );
        //echo '<p>Loading '.count( $modules ).' modules</p>';
        if ( !empty($modules) )
        {
            foreach( $modules as $config )
            {
                $post_types_dir = $config[ 'path' ].'/post_types/';
                if ( is_dir( $post_types_dir ) )
                {
                    //echo '<p>Dir '.$post_types_dir.' is valid</p>';
                    $handle = opendir( $post_types_dir );
                    while( false !== ( $file = readdir( $handle ) ) )
                    {
                        if ( strpos( $file, '_post_type.php' ) )
                        {
                            include_once( $post_types_dir.$file );
                            $class_name = str_replace( '.php', '', $file );
                            $classes[ $class_name ] = new $class_name();
                            //echo '<p>Loaded file '.$file.'</p>';
                        }
                    }
                }
            }
            $this->setOption( 'classes', $classes );
        }
    }

    /**
     * executeHooks
     * A simple hooks launcher
     *
     * @return void
     * @author Ramy Deeb
     */
    public function executeHooks()
    {
        do_action( 'zerobase_load_modules', $this );
    }

    /**
     * configurePostTypes
     * Configure the registered post types
     *
     * @return void
     * @author Ramy Deeb
     */
    public function configurePostTypes()
    {
        $classes = $this->getOption( 'classes', array() );
        if ( !empty( $classes ) )
        {
            foreach ( $classes as $class )
            {
                /** @var $class \zerobase_base_post_type */
                $class->registerPostType();
            }
        }

    }

    /**
     * configureTaxonomies
     * Configure the registered taxonomies
     *
     * @return void
     * @author Ramy Deeb
     */
    public function configureTaxonomies()
    {
        $classes = $this->getOption( 'classes', array() );
        if ( !empty( $classes ) )
        {
            foreach ( $classes as $class )
            {
                /** @var $class \zerobase_base_post_type */
                $class->registerTaxonomy();
            }
        }

    }

    /**
     * saveMetaboxesData
     * Saves the custom meta info for the post
     *
     * @param int $post_ID The Post ID
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function saveMetaboxesData( $post_ID, $object )
    {
        $classes = $this->getOption( 'classes', array() );
        if ( !empty( $classes ) )
        {
            foreach ( $classes as $class )
            {
                /** @var $class \zerobase_base_post_type */
                foreach ($class->getMetaboxes() as $metabox)
                {
                    /** @var $metabox \zerobase_metabox */
                    $metabox->save_meta_info( $post_ID, $object );
                }
            }
        }
    }

    /**
     * registerScripts Registers the framework scripts
     *
     * @return void
     * @author Ramy Deeb
     */
    public function registerAdminScripts()
    {
        //Register the color picker script & styles
        wp_register_script(
            'zerobase_js_colorpicker',
            plugins_url( '/assets/js/colorpicker.min.js' , __FILE__ ),
            array(
                'jquery'
            ),
            NULL,
            true
        );
        wp_register_style(
            'zerobase_css_colorpicker',
            plugins_url( '/assets/css/colorpicker.css' , __FILE__ )
        );
        wp_register_script(
            'zerobase_google_maps',
            '//maps.googleapis.com/maps/api/js?key=AIzaSyCNDR8-dYvuAmUyFRImtpAHfYznAeTolH4&sensor=true'
        );
        wp_register_script(
            'zerobase_js_forms',
            plugins_url( '/assets/js/forms.js' , __FILE__ ),
            array(
                'jquery',
                'jquery-ui-core',
                'jquery-ui-widget',
                'jquery-ui-datepicker',
                'media-upload',
                'thickbox',
                'zerobase_google_maps'
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
            plugins_url( '/assets/css/forms.min.css' , __FILE__ ),
            array(
                'thickbox'
            )
        );
        wp_enqueue_style( 'zerobase_css_forms' );
        wp_enqueue_script( 'zerobase_js_forms' );
    }

    /**
     * slugify
     * Creates a slug from a string
     *
     * @param $text Text to slugify
     *
     * @return string
     * @author Ramy Deeb
     */
    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

}

$zb_platform = zerobase_platform::getInstance();