<?php
namespace Zerobase\Modules;

use Symfony\Component\Yaml\Yaml;
use Zerobase\Cache\FileCache;
use Zerobase\Modules\Importers\MetaboxImporter;
use Zerobase\Modules\Importers\PostTypeImporter;
use Zerobase\Modules\Importers\TaxonomyImporter;
use Zerobase\Toolkit\Singleton;

class ModuleLoader extends Singleton {
    protected $modules           = array();
    protected $post_types_loaded = false;
    protected $taxonomies_loaded = false;
    protected $metaboxes_loaded  = false;
    protected $widgets_loaded    = false;
    protected $scripts_loaded    = false;

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
        if ( $cache_enabled )
        {
            if ($this->tryLoadCache())
            {
                return true;
            }
        }
        return $this->loadYamlFiles();
    }

    private function tryLoadCache()
    {
        $this->post_types_loaded = $this->loadCacheSegment( 'post_types' );
        $this->taxonomies_loaded = $this->loadCacheSegment( 'taxonomies' );
        $this->metaboxes_loaded  = $this->loadCacheSegment( 'metaboxes' );
        $this->widgets_loaded    = $this->loadCacheSegment( 'widgets' );
        $this->scripts_loaded    = $this->loadCacheSegment( 'scripts' );

        if ( !$this->post_types_loaded || !$this->taxonomies_loaded || !$this->metaboxes_loaded || !$this->widgets_loaded || !$this->scripts_loaded )
        {
            return false;
        }
        return true;
    }

    private function loadCacheSegment( $cache )
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

    private function loadYamlFiles()
    {
        $cache_enabled = (bool) get_option( 'zerobase_platform_cache', TRUE );
        foreach( $this->modules as $index => $config )
        {
            foreach ( $this->getYamlFilesFromDir( $config['path'], '.yml' ) as $file )
            {
                if ( strpos( $file, 'post_type' ) !== false && !$this->post_types_loaded )
                {
                    $this->loadPostTypeFromYaml( $file, $cache_enabled );
                    $this->post_types_loaded = true;
                }
                else if ( strpos( $file, 'taxonomy' ) !== false && !$this->taxonomies_loaded )
                {
                    $this->loadTaxonomyFromYaml( $file, $cache_enabled );
                    $this->taxonomies_loaded = true;
                }
                else if ( strpos( $file, 'metabox' ) !== false && !$this->metaboxes_loaded )
                {
                    $this->loadMetaboxesFromYaml( $file, $cache_enabled );
                    $this->metaboxes_loaded = true;
                }
                else if ( strpos( $file, 'widget' ) !== false && !$this->widgets_loaded )
                {
                    $this->widgets_loaded = true;
                }
                else if ( strpos( $file, 'script' ) !== false && !$this->scripts_loaded )
                {
                    $this->scripts_loaded = true;
                }
            }
        }
        return true;
    }

    private function loadPostTypeFromYaml( $file, $cache_enabled = true )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result = Yaml::parse($file_contents);
        foreach ( $yaml_result as $post_type_name => $post_type_config )
        {
            if ( $post_type_name )
            {
                if ( !post_type_exists( $post_type_name ) )
                {
                    PostTypeImporter::load( $post_type_name, $post_type_config );
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
        $yaml_result = Yaml::parse($file_contents);
        foreach ( $yaml_result as $taxonomy_name => $taxonomy_config )
        {
            if ( $taxonomy_name )
            {
                if ( !taxonomy_exists( $taxonomy_name ) )
                {
                    TaxonomyImporter::load( $taxonomy_name, $taxonomy_config );
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

    private function loadMetaboxesFromYaml( $file, $cache_enabled = true )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result = Yaml::parse($file_contents);
        foreach ( $yaml_result as $metabox_name => $metabox_config )
        {
            if ( $metabox_name )
            {
                MetaboxImporter::load( $metabox_name, $metabox_config );
                if ( $cache_enabled )
                {
                    $cache_bag = FileCache::getInstance()->createCache( 'config' );
                    $loaded_metaboxes = $cache_bag->retreive( 'cached_metaboxes' );
                    if ( $loaded_metaboxes === false )
                    {
                        $loaded_metaboxes = array();
                    }
                    $loaded_metaboxes[] = $metabox_name;
                    $cache_bag->store( 'cached_metaboxes', '<?php return ' . var_export( $loaded_metaboxes, true ) . ' ?>' );
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
