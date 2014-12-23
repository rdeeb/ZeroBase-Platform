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
require_once( __DIR__.'/library/toolkit/ZB_Singleton.php' );

class ZerobasePlatform extends ZB_Singleton
{
    private $dataStorage = array();
    CONST ZEROBASE_ADMIN_PAGE_PREFIX = 'zerobase_settings_page_';
    /**
     * __construct Initializes the platform
     *
     * @author Ramy Deeb
     */
    protected function __construct()
    {
        //Load the files
        $this->loadPlatformRequiredFiles();
        //Install platform terms tables
        $this->installPlatformTermsTables();
        //Configure Options and Settings
        $this->configurePlatformOptions();
        //Set the required Wordpress hooks
        $this->addWordpressActionHooks();
        //Load the different locales
        load_plugin_textdomain('zerobase', FALSE, dirname(plugin_basename(__FILE__)).'/locales/');
    }

    /**
     * Returns the platform library path
     * @return string
     */
    private function getPlatformLibraryDir()
    {
        // Define the plugin base directory
        $dir = plugin_dir_path( __FILE__ );
        return $dir.'/library';
    }

    /**
     * Loads the required files for the platform
     */
    private function loadPlatformRequiredFiles()
    {
        $libraryPath = $this->getPlatformLibraryDir();
        //Toolkit
        require_once( $libraryPath . '/toolkit/ZB_HtmlToolkit.php' );
        //Forms
        require_once( $libraryPath . '/forms/widgets/ZB_WidgetFactory.php' );
        require_once( $libraryPath . '/forms/ZB_Form.php' );
        require_once( $libraryPath . '/forms/ZB_FormFactory.php' );
        //Metaboxes
        require_once( $libraryPath . '/metaboxes/ZB_Metabox.php' );
        //Post Types
        require_once( $libraryPath . '/post-types/ZB_PostTypeInterface.php' );
        require_once( $libraryPath . '/post-types/ZB_BasePostType.php' );
        //Taxonomiy extender
        require_once( $libraryPath . '/taxonomies/ZB_TaxonomyExtender.php' );
        //Widgets
        require_once( $libraryPath . '/widgets/ZB_BaseWidget.php' );
        //Settings
        require_once( $libraryPath . '/settings/ZB_Settings.php' );
        require_once( $libraryPath . '/settings/ZB_SettingsBag.php' );
    }

    /**
     * Creates a terms table for the platform if it doesn't exists
     */
    private function installPlatformTermsTables()
    {
        //Extend the database
        require_once( plugin_dir_path( __FILE__ ) . '/installation/zerobase_create_tables.php' );
        global $wpdb;
        $type = 'zerobase_term';
        $table_name = $wpdb->prefix . $type . 'meta';
        $variable_name = $type . 'meta';
        $wpdb->$variable_name = $table_name;
        zerobase_create_metadata_table( $table_name, $type );
    }

    /**
     * Sets up the options and settings of the platform
     */
    private function configurePlatformOptions()
    {
        $this->dataStorage = get_option( 'zerobase_platform_data_storage', array() );
        if ( empty( $this->dataStorage ) )
        {
            $this->storeKeyValueData( 'version', '0.2' );
        }
        //Configure the basic settings bag
        if (is_admin())
        {
            $this->initSettingsBag();
        }
    }

    /**
     * Adds the necesary wordpress action hooks
     */
    private function addWordpressActionHooks()
    {
        //Adding Actions;
        add_action( 'plugins_loaded', array( &$this, 'executeAfterPluginsSetupHooks'), 1 );
        add_action( 'after_setup_theme', array( &$this, 'executeAfterThemeSetupHooks'), 1 );
        add_action( 'after_setup_theme', array( &$this, 'loadModules' ), 2 );
        add_action( 'init', array( &$this, 'registerPostTypes'), 10 );
        add_action( 'init', array( &$this, 'registerTaxonomies'), 11 );
        //Configuring the Admin panel
        if (is_admin())
        {
            add_action( 'admin_menu', array( &$this, 'addAdminSettingsPage'), 2 );
            //Register the framework scripts and styles
            add_action( 'admin_enqueue_scripts', array( &$this, 'registerAdminScripts' ), 1 );
        }
    }

    /**
     * Stores a key value pair set of data
     * @param $key
     * @param $value
     */
    private function storeKeyValueData( $key, $value )
    {
        $this->dataStorage[ $key ] = $value;
        add_option( 'zerobase_platform_data_storage', $this->dataStorage );
    }

    /**
     * Retreive the value of a key
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    private function retreiveKeyValueData( $key, $default = null )
    {
        if ( isset( $this->dataStorage[ $key ] ) )
        {
            return $this->dataStorage[ $key ];
        }
        else
        {
            return $default;
        }
    }

    /**
     * addModule Adds a new module to the platform
     * @param array $config defines de configuration parameters of the module being loaded
     * @throws Exception
     */
    public function addModule( array $config )
    {
        try
        {
            $this->validateModuleConfiguration($config);
            $modules = $this->retreiveKeyValueData( 'modules', array() );
            $modules[ self::slugify( $config[ 'name' ] ) ] = $config;
            $this->storeKeyValueData( 'modules', $modules );
        }
        catch(Exception $e)
        {
            //TODO: Show a message to the user telling him that the module wasn't loaded
        }
    }

    /**
     * Validates if the mandatory parameters are present in the configurations
     * @param array $config
     * @return bool
     * @throws Exception
     */
    private function validateModuleConfiguration( array $config )
    {
        if (!isset($config[ 'path' ]) || empty($config[ 'path' ]) || !is_dir( $config[ 'path' ] ))
        {
            throw new Exception('Every module must define a valid path');
        }
        if ( !isset( $config[ 'name' ] ) )
        {
            throw new Exception('Every module must define a name');
        }
        return true;
    }

    public function loadModules()
    {
        $modules = $this->retreiveKeyValueData( 'modules', array() );

        if ( !empty($modules) )
        {
            foreach( $modules as $config )
            {
                $this->loadPostType( $config );
            }

        }
    }

    /**
     * Loads an specific post type to the platform
     * @param array $config
     */
    private function loadPostType( array $config )
    {
        $classes = $this->retreiveKeyValueData( 'classes', array() );
        $post_types_dir = $config[ 'path' ].'/post_types/';
        if ( is_dir( $post_types_dir ) )
        {
            $handle = opendir( $post_types_dir );
            while( false !== ( $file = readdir( $handle ) ) )
            {
                if ( strpos( $file, '_post_type.php' ) )
                {
                    include_once( $post_types_dir.$file );
                    $class_name = str_replace( '.php', '', $file );
                    $classes[ $class_name ] = new $class_name();
                }
            }
        }
        $this->storeKeyValueData( 'classes', $classes );
    }

    public function executeAfterThemeSetupHooks()
    {
        do_action( 'zerobase_load_modules', $this );
    }

    public function executeAfterPluginsSetupHooks()
    {
        do_action( 'zerobase_load_plugins', $this );
    }

    public function registerPostTypes()
    {
        $classes = $this->retreiveKeyValueData( 'classes', array() );
        if ( !empty( $classes ) )
        {
            foreach ( $classes as $class )
            {
                /** @var $class \ZB_BasePostType */
                $class->registerPostType();
                $class->registerMetaboxes();
                if (is_admin())
                {
                    $this->registerPostTypesSettings( $class );
                }
            }
        }
    }

    /**
     * Register the settings of an specific post type
     */
    private function registerPostTypesSettings(ZB_PostTypeInterface $class)
    {
        $settings = ZB_Settings::getInstance();
        $postTypeBag = $settings->getBag('post_types');
        foreach($class->getOptions() as $key => $options)
        {
            $postTypeBag->addSetting($key, $options['widget'], $options, $class->getName());
        }
    }
    /**
     * Register the post type taxonomies
     */
    public function registerTaxonomies()
    {
        $classes = $this->retreiveKeyValueData( 'classes', array() );
        if ( !empty( $classes ) )
        {
            foreach ( $classes as $class )
            {
                /** @var $class \ZB_BasePostType */
                $class->registerTaxonomy();
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
        wp_register_style(
            'zerobase_uikit',
            plugins_url( '/assets/lib/uikit/css/uikit.min.css' , __FILE__ )
        );
        wp_register_style(
            'zerobase_uikit_almost_flat',
            plugins_url( '/assets/lib/uikit/css/uikit.almost-flat.min.css' , __FILE__ ),
            array(
                'zerobase_uikit'
            )
        );
        wp_register_script(
            'zerobase_google_maps',
            '//maps.googleapis.com/maps/api/js?key=AIzaSyCNDR8-dYvuAmUyFRImtpAHfYznAeTolH4&sensor=true'
        );
        wp_register_script(
            'zerobase_uikit_js',
            plugins_url( '/assets/lib/uikit/js/uikit.min.js' , __FILE__ ),
            array(),
            NULL,
            true
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
                'zerobase_google_maps',
                'zerobase_uikit_js'
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
            plugins_url( '/assets/css/forms.css' , __FILE__ ),
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
    static function slugify($text)
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

    /**
     * Initializes the settings bags
     * @throws Exception
     */
    public function initSettingsBag()
    {
        $settings = ZB_Settings::getInstance();
        $settings->createBag('platform');
        $settings->createBag('layout');
        $settings->createBag('post_types');
        $platform = $settings->getBag('platform');
        $platform->addSetting('platform_use_cdn', 'radio_list', array(
            'widget_options' => array(
                'choices' => array(
                    'cdn' => __('CDN copies of the files (faster)', 'zerobase'),
                    'local' =>  __('Local copies of the libraries')
                )
            ),
            'default' => 'local'
        ));
    }

    /**
     * Adds the admin pages to the admin page in Wordpress
     */
    public function addAdminSettingsPage()
    {
        $zerobase_settings_page = add_menu_page(__('Zerobase Options', 'zerobase'), __('Zerobase Options','zerobase'), 'manage_options', 'zerobase-settings', array($this, self::ZEROBASE_ADMIN_PAGE_PREFIX.'platform'), null, 100);
        $settings = ZB_Settings::getInstance();
        foreach($settings as $key => $bag)
        {
            /** @var $bag ZB_SettingsBag */
            if ($key != 'platform' && !$bag->isEmpty())
            {
                add_submenu_page( 'zerobase-settings', __($key, 'zerobase'), __($key, 'zerobase'), 'manage_options', 'zerobase-settings-'.$key, array($this, self::ZEROBASE_ADMIN_PAGE_PREFIX.$key) );
            }
        }
    }

    /**
     * Renders an Option page from its bag name
     * @param string $bagName
     */
    public function renderOptionPage($bagName)
    {
        $dir = plugin_dir_path( __FILE__ );
        $lib_dir = $dir.'/library';
        $page_name = __($bagName, 'zerobase');
        $settings = ZB_Settings::getInstance();
        $bag = $settings->getBag($bagName);
        $settings_pages = $bag->getPages($bagName);
        wp_enqueue_media();
        include( $lib_dir . '/settings/template/base.php' );
    }

    /**
     * We use this function to handle the calls to create option pages
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        if (strpos($name, self::ZEROBASE_ADMIN_PAGE_PREFIX) !== false)
        {
            $page = str_replace(self::ZEROBASE_ADMIN_PAGE_PREFIX, '', $name);
            $this->renderOptionPage($page);
        }
        else
        {
            throw new BadFunctionCallException("Function \"$name\" doesn't exists in the ZeroBase Platform");
        }
    }
}
//Instanciate the platform
$zb_platform = ZerobasePlatform::getInstance();