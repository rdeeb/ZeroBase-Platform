<?php
namespace Zerobase\Modules;

use Symfony\Component\Yaml\Yaml;
use Zerobase\Cache\FileCache;
use Zerobase\Modules\Importers\MetaboxImporter;
use Zerobase\Modules\Importers\PostTypeImporter;
use Zerobase\Modules\Importers\ScriptsImporter;
use Zerobase\Modules\Importers\TaxonomyImporter;
use Zerobase\Modules\Importers\WidgetImporter;
use Zerobase\Toolkit\Singleton;

class ModuleLoader extends Singleton {
    protected $modules           = array();
    protected $post_types_loaded = false;
    protected $taxonomies_loaded = false;
    protected $metaboxes_loaded  = false;
    protected $widgets_loaded    = false;
    protected $scripts_loaded    = false;
    protected $loaded_segments   = array();
    protected $loaded            = false;

    public function addModule( $name, array $module_config ) {
        $this->modules[$name] = $module_config;
    }

    public function getModuleList()
    {
        return $this->modules;
    }

    public function load() {
        if ( !$this->loaded )
        {
            $cache_enabled = (bool) get_option( 'zerobase_platform_cache', TRUE );
            //Load from Cache
            if ( $cache_enabled )
            {
                if ($this->tryLoadCache())
                {
                    $this->loaded = true;
                    return true;
                }
            }
            $this->loaded = true;
            return $this->loadYamlFiles();
        }
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
        if ( !isset( $this->loaded_segments[ $cache ] ) || $this->loaded_segments[ $cache ] === false )
        {
            $cache_bag = FileCache::getInstance()->createCache( 'config' );
            $loaded_objects = $cache_bag->retreive( 'cached_' . $cache );
            if ( $loaded_objects !== false )
            {
                if ( !empty( $loaded_objects ) )
                {
                    foreach( $loaded_objects as $cache_key )
                    {
                        $cache_bag = FileCache::getInstance()->createCache( $cache );
                        if ( $cache != 'widgets' || !class_exists( $cache_key )  )
                        {
                            $object = $cache_bag->retreive( $cache_key );
                            if ( $object === false )
                            {
                                return false;
                            }
                        }
                    }
                    $this->loaded_segments[ $cache ] = true;
                    return true;
                }
                return false;
            }
            return false;
        }
        else
        {
            return true;
        }
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
                    $this->loadWidgetsFromYaml( $file, $cache_enabled );
                    $this->widgets_loaded = true;
                }
                else if ( strpos( $file, 'script' ) !== false && !$this->scripts_loaded )
                {
                    $this->loadScriptsFromYaml( $file, $cache_enabled );
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

    private function loadWidgetsFromYaml( $file, $cache_enabled = true )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result = Yaml::parse($file_contents);
        foreach ( $yaml_result as $widget_name => $widget_config )
        {
            if ( $widget_name )
            {
                $widget_config[ 'base_path' ] = plugin_dir_path( $file );
                WidgetImporter::load( $widget_name, $widget_config );
                if ( $cache_enabled )
                {
                    $cache_bag = FileCache::getInstance()->createCache( 'config' );
                    $loaded_widgets = $cache_bag->retreive( 'cached_widgets' );
                    if ( $loaded_widgets === false )
                    {
                        $loaded_widgets = array();
                    }
                    $loaded_widgets[] = $widget_name;
                    $cache_bag->store( 'cached_widgets', '<?php return ' . var_export( $loaded_widgets, true ) . ' ?>' );
                }
            }
        }
    }

    private function loadScriptsFromYaml( $file, $cache_enabled = true )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result = Yaml::parse($file_contents);
        if ( $cache_enabled )
        {
            $cache_bag = FileCache::getInstance()->createCache( 'config' );
            ScriptsImporter::load( $file, $yaml_result );
            $loaded_scripts = $cache_bag->retreive( 'cached_scriptss' );
            if ( $loaded_scripts === false )
            {
                $loaded_scripts = array();
            }
            $loaded_scripts[] = $file;
            $cache_bag->store( 'cached_scriptss', '<?php return ' . var_export( $loaded_scripts, true ) . ' ?>' );
        }
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
