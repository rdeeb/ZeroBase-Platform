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
require_once(__DIR__.'/autoloader.php');

class ZerobasePlatform extends \Zerobase\Toolkit\Singleton
{
    protected $init = false;
    CONST ZEROBASE_ADMIN_PAGE_PREFIX = 'zerobase_settings_page_';
    

    /**
     * __construct Initializes the platform
     *
     * @author Ramy Deeb
     */
    protected function __construct()
    {
        if ( !$this->init )
        {
            DEFINE( 'ZEROBASE_ROOT_DIR', plugin_dir_path( __FILE__ ));
            DEFINE( 'ZEROBASE_LIBRARY_DIR', ZEROBASE_ROOT_DIR . '/library' );
            DEFINE( 'ZEROBASE_VENDOR_DIR', ZEROBASE_ROOT_DIR . '/vendor' );
            DEFINE( 'ZEROBASE_CACHE_DIR', ZEROBASE_ROOT_DIR . '/cache' );
            //Install platform terms tables
            $this->installPlatformTermsTables();
            //Set the required Wordpress hooks
            $this->addWordpressActionHooks();
            //Load the different locales
            load_plugin_textdomain( 'zerobase', false, dirname( plugin_basename( __FILE__ ) ) . '/locales/' );
            if ( !is_dir( ZEROBASE_CACHE_DIR ) ) {
                mkdir( ZEROBASE_CACHE_DIR, 755 );
            }
            $this->init = true;
        }
    }

    /**
     * Creates a terms table for the platform if it doesn't exists
     */
    private function installPlatformTermsTables()
    {
        //Extend the database
        require_once( ZEROBASE_ROOT_DIR . '/installation/ZB_Installer.php' );
        global $wpdb;
        $type = 'zerobase_term';
        $table_name = $wpdb->prefix . $type . 'meta';
        $variable_name = $type . 'meta';
        $wpdb->$variable_name = $table_name;
        ZB_Installer::createMetadataTable( $table_name, $type );
    }

    /**
     * Sets up the options and settings of the platform
     */
    public function configurePlatformOptions()
    {
        //Configure the basic settings bag
        if ( is_admin() ) {
            $this->initSettingsBag();
        }
    }

    /**
     * Adds the necesary wordpress action hooks
     */
    private function addWordpressActionHooks()
    {
        //Adding Actions;
        add_action( 'plugins_loaded', array( &$this, 'executeAfterPluginsSetupHooks' ), 1 );
        add_action( 'after_setup_theme', array( &$this, 'executeAfterThemeSetupHooks' ), 1 );
        add_action( 'after_setup_theme', array( &$this, 'registerModules' ), 10 );
        add_action( 'init', array( &$this, 'configurePlatformOptions' ), 11 );
        //Configuring the Admin panel
        if ( is_admin() ) {
            add_action( 'admin_menu', array( &$this, 'addAdminSettingsPage' ), 2 );
            //Register the framework scripts and styles
            add_action( 'admin_enqueue_scripts', array( &$this, 'registerAdminScripts' ), 1 );
        }
    }

    /**
     * addModule Adds a new module to the platform
     *
     * @param array $config defines de configuration parameters of the module being loaded
     *
     * @throws Exception
     */
    public function addModule( array $config )
    {
        try
        {
            $this->validateModuleConfiguration( $config );
            $module_loader = \Zerobase\Modules\ModuleLoader::getInstance();
            $module_loader->addModule( self::slugify( $config[ 'name' ] ), $config );
        }
        catch ( Exception $e ) {}
    }

    /**
     * Validates if the mandatory parameters are present in the configurations
     *
     * @param array $config
     *
     * @return bool
     * @throws Exception
     */
    private function validateModuleConfiguration( array $config )
    {
        if ( !isset( $config[ 'path' ] ) || empty( $config[ 'path' ] ) || !is_dir( $config[ 'path' ] ) ) {
            throw new Exception( 'Every module must define a valid path' );
        }
        if ( !isset( $config[ 'name' ] ) ) {
            throw new Exception( 'Every module must define a name' );
        }

        return true;
    }

    public function executeAfterThemeSetupHooks()
    {
        do_action( 'zerobase_load_modules', $this );
    }

    public function executeAfterPluginsSetupHooks()
    {
        do_action( 'zerobase_load_plugins', $this );
    }

    public function registerModules()
    {
        $module_loader = \Zerobase\Modules\ModuleLoader::getInstance();
        $module_loader->load();
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
            plugins_url( '/assets/js/colorpicker.min.js', __FILE__ ),
            array(
                'jquery'
            ),
            null,
            true
        );
        wp_register_style(
            'zerobase_css_colorpicker',
            plugins_url( '/assets/css/colorpicker.css', __FILE__ )
        );
        wp_register_style(
            'zerobase_uikit',
            plugins_url( '/assets/lib/uikit/css/uikit.min.css', __FILE__ )
        );
        wp_register_style(
            'zerobase_uikit_almost_flat',
            plugins_url( '/assets/lib/uikit/css/uikit.almost-flat.min.css', __FILE__ ),
            array(
                'zerobase_uikit'
            )
        );
        if ( get_option( 'zerobase_platform_google_maps_key' ) )
        {
            wp_register_script(
              'zerobase_google_maps',
              '//maps.googleapis.com/maps/api/js?key=' . get_option( 'zerobase_platform_google_maps_key' ) . '&sensor=true'
            );
        }
        wp_register_script(
            'zerobase_uikit_js',
            plugins_url( '/assets/lib/uikit/js/uikit.min.js', __FILE__ ),
            array(),
            null,
            true
        );
        wp_register_script(
          'zerobase_js_libs',
          plugins_url( '/assets/js/libs.js', __FILE__ ),
          array(
            'jquery'
          ),
          null,
          true
        );
        wp_register_script(
            'zerobase_js_forms',
            plugins_url( '/assets/js/forms.js', __FILE__ ),
            array(
                'jquery',
                'jquery-ui-core',
                'jquery-ui-widget',
                'jquery-ui-datepicker',
                'media-upload',
                'thickbox',
                'zerobase_google_maps',
                'zerobase_uikit_js',
                'zerobase_js_libs'
            ),
            null,
            true
        );
        wp_localize_script( 'zerobase_js_forms', 'forms_trans', array(
            'gallery_title'  => __( 'Select the images for the gallery', 'zerobase' ),
            'gallery_submit' => __( 'Choose gallery', 'zerobase' ),
            'image_title'    => __( 'Select an image', 'zerobase' ),
            'image_submit'   => __( 'Choose image', 'zerobase' ),
            'file_title'     => __( 'Select a file', 'zerobase' ),
            'file_submit'    => __( 'Choose file', 'zerobase' ),
            'map' => array(
                'click_to_zoom' => __( 'Click to zoom', 'zerobase' ),
            )
        ) );
        if ( get_option( 'zerobase_platform_google_maps_key', NULL ) )
        {
            wp_localize_script( 'zerobase_google_maps', 'maps_config', array(
              'latitude'  => get_option( 'zerobase_platform_google_maps_latitude', '9.040860' ),
              'longitude' => get_option( 'zerobase_platform_google_maps_longitude', '-79.483337' ),
            ) );
        }
        wp_register_style(
            'zerobase_css_forms',
            plugins_url( '/assets/css/forms.css', __FILE__ ),
            array(
                'thickbox',
                'zerobase_uikit'
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
    static function slugify( $text )
    {
        // replace non letter or digits by -
        $text = preg_replace( '~[^\\pL\d]+~u', '-', $text );

        // trim
        $text = trim( $text, '-' );

        // transliterate
        $text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );

        // lowercase
        $text = strtolower( $text );

        // remove unwanted characters
        $text = preg_replace( '~[^-\w]+~', '', $text );

        if ( empty( $text ) ) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * Initializes the settings bags
     * @throws Exception
     */
    public function initSettingsBag()
    {
        $settings = \Zerobase\Settings\Settings::getInstance();
        $settings->createBag( 'platform' );
        $settings->createBag( 'layout' );
        $settings->createBag( 'zerobase-plugin' );
        //Performance Options
        $platform = $settings->getBag( 'platform' );
        $platform
            ->addSetting( 'zerobase_platform_use_cdn', 'radio_list', array(
                'widget_options' => array(
                    'label' => 'Use CDN for JS library files',
                    'choices' => array(
                        '1'   => __( 'CDN copies of the files (faster)', 'zerobase' ),
                        '0' => __( 'Local copies of the libraries', 'zerobase' )
                    )
                ),
                'default'        => '1'
            ), 'Performance' )
            ->addSetting( 'zerobase_platform_cache', 'radio_list', array(
                'widget_options' => array(
                    'choices' => array(
                        '1'   => __( 'Enabled (Recomended)', 'zerobase' ),
                        '0' => __( 'Disabled (For developing use only, hurts performance)', 'zerobase' )
                    )
                ),
                'default'        => '1'
            ), 'Performance' )
            ->addSetting( 'zerobase_platform_google_maps_key', 'text', array(
                'widget_options' => array(
                    'label' => 'Google Maps API Key'
                ),
                'default'        => ''
            ), 'google-maps' )
        ;
        if ( get_option( 'zerobase_platform_google_maps_key' ) )
        {
            $platform
              ->addSetting( 'zerobase_platform_google_maps_latitude', 'text', array(
                'widget_options' => array(
                  'label' => 'Default latitude'
                ),
                'default'        => '9.040860'
              ), 'google-maps' )
              ->addSetting( 'zerobase_platform_google_maps_longitude', 'text', array(
                'widget_options' => array(
                  'label' => 'Default longitude'
                ),
                'default'        => '-79.483337'
              ), 'google-maps' );
        }
        $module_list = \Zerobase\Modules\ModuleLoader::getInstance()->getModuleList();
        $module_bag = $settings->getBag( 'zerobase-plugin' );
        foreach( $module_list as $module => $config )
        {
            $module_bag->addSetting( "zerobase_module_$module", 'checkbox', array(
              'widget_options' => array(
                'label' => $config['name'],
                'choices' => array(
                  '1'   => __( 'Enabled', 'zerobase' ),
                  '0' => __( 'Disabled', 'zerobase' )
                )
              ),
              'default'        => '1'
            ) );
        }
    }

    /**
     * Adds the admin pages to the admin page in Wordpress
     */
    public function addAdminSettingsPage()
    {
        add_menu_page( __( 'Zerobase Options', 'zerobase' ), __( 'Zerobase Options', 'zerobase' ), 'manage_options', 'zerobase-settings', array( $this, self::ZEROBASE_ADMIN_PAGE_PREFIX . 'platform' ), null, 100 );
        $settings = \Zerobase\Settings\Settings::getInstance();
        foreach ( $settings as $key => $bag ) {
            /** @var $bag ZB_SettingsBag */
            if ( $key != 'platform' && !$bag->isEmpty() ) {
                add_submenu_page( 'zerobase-settings', __( $key, 'zerobase' ), __( $key, 'zerobase' ), 'manage_options', 'zerobase-settings-' . $key, array( $this, self::ZEROBASE_ADMIN_PAGE_PREFIX . $key ) );
            }
        }
    }

    /**
     * Renders an Option page from its bag name
     *
     * @param string $bagName
     */
    public function renderOptionPage( $bagName )
    {
        $dir = plugin_dir_path( __FILE__ );
        $lib_dir = $dir . '/library';
        $page_name = __( $bagName, 'zerobase' );
        $settings = Zerobase\Settings\Settings::getInstance();
        $bag = $settings->getBag( $bagName );
        $settings_pages = $bag->getPages( $bagName );
        if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
            foreach ( $settings_pages as $key => $form ) {
                if ( $form->isValid() ) {
                    $form->save();
                }
            }
        }
        wp_enqueue_media();
        include( $lib_dir . '/settings/template/base.php' );
    }

    /**
     * We use this function to handle the calls to create option pages
     *
     * @param string $name
     * @param array  $arguments
     */
    public function __call( $name, $arguments )
    {
        if ( strpos( $name, self::ZEROBASE_ADMIN_PAGE_PREFIX ) !== false ) {
            $page = str_replace( self::ZEROBASE_ADMIN_PAGE_PREFIX, '', $name );
            $this->renderOptionPage( $page );
        } else {
            throw new BadFunctionCallException( "Function \"$name\" doesn't exists in the ZeroBase Platform" );
        }
    }
}

//Instantiate the platform
$zb_platform = ZerobasePlatform::getInstance();
