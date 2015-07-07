<?php
namespace Zerobase\Modules;

use Zerobase\Cache\FileCache;
use Zerobase\Toolkit\Singleton;

class ModuleLoader extends Singleton {
    protected $modules = array();

    public function addModule( $name, array $module_config ) {
        $this->modules[$name] = $module_config;
    }

    public function getModuleList()
    {
        return $this->modules;
    }

    public function load() {
        $cache_enabled = (bool) get_option( 'zerobase_platform_cache', TRUE );
        //Load from Cache
        $post_types_loaded = false;
        $taxonomies_loaded = false;
        $metaboxes_loaded  = false;
        $widgets_loaded    = false;
        $scripts_loaded    = false;
        if ( $cache_enabled )
        {
            $post_types_loaded = $this->loadFromCache( 'post_types' );
            $taxonomies_loaded = $this->loadFromCache( 'taxonomies' );
            $metaboxes_loaded  = $this->loadFromCache( 'metaboxes' );
            $widgets_loaded    = $this->loadFromCache( 'widgets' );
            $scripts_loaded    = $this->loadFromCache( 'scripts' );
        }
        if ( !$post_types_loaded || !$taxonomies_loaded || !$metaboxes_loaded || !$widgets_loaded || !$scripts_loaded )
        {
            foreach( $this->modules as $index => $config )
            {
                foreach ( $this->getYamlFilesFromDir( $config['path'], '.yml' ) as $file )
                {
                    if ( strpos( $file, 'post_type' ) !== false && !$post_types_loaded )
                    {
                        $this->loadPostTypeFromYaml( $file, $cache_enabled );
                    }
                    else if ( strpos( $file, 'taxonomy' ) !== false && !$taxonomies_loaded )
                    {
                        $this->loadTaxonomyFromYaml( $file, $cache_enabled );
                    }
                    else if ( strpos( $file, 'metabox' ) !== false && !$metaboxes_loaded )
                    {

                    }
                    else if ( strpos( $file, 'widget' ) !== false && !$widgets_loaded )
                    {

                    }
                    else if ( strpos( $file, 'script' ) !== false && !$scripts_loaded )
                    {

                    }
                }
            }
        }
    }

    private function loadFromCache( $cache )
    {
        $cache_bag = FileCache::getInstance()->createCache( 'config' );
        $loaded_post_types = $cache_bag->retreive( 'cached_' . $cache );
        if ( $loaded_post_types !== false )
        {
            if ( !empty( $loaded_post_types ) )
            {
                foreach( $loaded_post_types as $post_type_name )
                {
                    $cache_bag = FileCache::getInstance()->createCache( $cache );
                    $post_type = $cache_bag->retreive( $post_type_name );
                    if ( $post_type === false )
                    {
                        return false;
                    }
                }
                return true;
            }
            return false;
        }
        return false;
    }

    private function loadPostTypeFromYaml( $file, $cache_enabled = true )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
        foreach ( $yaml_result as $post_type_name => $post_type_config )
        {
            if ( $post_type_name )
            {
                if ( !post_type_exists( $post_type_name ) )
                {
                    ZB_PostTypeImporter::load( $post_type_name, $post_type_config );
                    if ( $cache_enabled )
                    {
                        $cache_bag = FileCache::getInstance()->createCache( 'config' );
                        $loaded_post_types = $cache_bag->retreive( 'cached_post_types' );
                        if ( $loaded_post_types === false )
                        {
                            $loaded_post_types = array();
                        }
                        $loaded_post_types[] = $post_type_name;
                        $cache_bag->store( 'cached_post_types', '<?php return ' . var_export( $loaded_post_types, true ) . ' ?>' );
                    }
                }
            }
        }
    }

    private function loadTaxonomyFromYaml( $file, $cache_enabled = true )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
        foreach ( $yaml_result as $taxonomy_name => $taxonomy_config )
        {
            if ( $taxonomy_name )
            {
                if ( !taxonomy_exists( $taxonomy_name ) )
                {
                    ZB_TaxonomyImporter::load( $taxonomy_name, $taxonomy_config );
                    if ( $cache_enabled )
                    {
                        $cache_bag = FileCache::getInstance()->createCache( 'config' );
                        $loaded_taxonomies = $cache_bag->retreive( 'cached_taxonomies' );
                        if ( $loaded_taxonomies === false )
                        {
                            $loaded_taxonomies = array();
                        }
                        $loaded_taxonomies[] = $taxonomy_name;
                        $cache_bag->store( 'cached_taxonomies', '<?php return ' . var_export( $loaded_taxonomies, true ) . ' ?>' );
                    }
                }
            }
        }
    }

    public function enqueue() {
        foreach( $this->modules as $index => $config )
        {
            $this->enqueueScripts( $config );
        }
    }

    private function loadMetaBoxes( array &$config ) {
        foreach ( $this->getYamlFilesFromDir( $config['path'], '.metabox.yml' ) as $file ) {
            $file_contents = file_get_contents( $file );
            $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
            $config['metaboxes'] = array();
            foreach ( $yaml_result as $metabox_name => $metabox_config ) {
                if ( $metabox_name ) {

                }
            }
        }
    }

    private function loadWidgets( array &$config ) {
        foreach ( $this->getYamlFilesFromDir( $config['path'], '.widget.yml' ) as $file ) {
            $file_contents = file_get_contents( $file );
            $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
            $config['widgets'] = array();
            foreach ( $yaml_result as $widget_name => $widget_config ) {
                if ( $widget_name ) {
                    $widget_config['id'] = $widget_name;
                    $object = new ZB_Metabox( $widget_config );
                    $config['widgets'][] = $object;
                }
            }
        }
    }

    private function loadTaxonomies() {
        foreach ( $this->getYamlFilesFromDir( '.taxonomy.yml' ) as $file ) {
            $file_contents = file_get_contents( $file );
            $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
            foreach ( $yaml_result as $post_type_name => $post_type_config ) {

                if ( $post_type_name ) {

                }
            }
        }
    }

    private function loadScripts( array &$config ) {
        foreach ( $this->getYamlFilesFromDir( $config['path'], '.script.yml' ) as $file ) {
            $file_contents = file_get_contents( $file );
            $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
            $config['scripts'] = array();
            foreach ( $yaml_result as $script_name => $script_config ) {
                if ( $script_name ) {
                    if (!isset($script_config['path'])) {
                        throw new Exception('A path for the script should be defined');
                    }
                    $script_config['path'] = plugin_dir_url( __FILE__ ) . $script_config['path'];
                    $this->registerScript( $script_name, $script_config );
                    $config['scripts'][] = $script_name;
                }
            }
        }
    }

    private function enqueueScripts( array $config ) {
        foreach( $config['scripts'] as $script_name ) {
            wp_enqueue_script( $script_name );
        }
    }

    private function registerScript( $name, array $config ) {
        $default = array(
          'dependencies'    => array(),
          'version'         => null,
          'in_footer'       => true
        );
        $config = array_merge( $config, $default );
        wp_register_script(
          $name,
          $config['path'],
          $config['dependencies'],
          $config['version'],
          $config['in_footer']
        );
    }

    private function getYamlFilesFromDir( $path, $mask = '.yml' ) {
        $post_types = array();
        foreach( scandir( $path ) as $file ) {
            if ( strpos( $file, $mask ) !== false ) {
                $post_types[] = $path . '/' . $file;
            }
        }
        return $post_types;
    }
}