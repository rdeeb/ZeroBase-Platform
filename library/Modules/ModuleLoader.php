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

class ModuleLoader extends Singleton
{
    protected $modules = array();
    protected $post_types_loaded = FALSE;
    protected $taxonomies_loaded = FALSE;
    protected $metaboxes_loaded = FALSE;
    protected $widgets_loaded = FALSE;
    protected $scripts_loaded = FALSE;
    protected $loaded_segments = array();
    protected $loaded = FALSE;

    public function addModule( $name, array $module_config )
    {
        $this->modules[ $name ] = $module_config;
    }

    public function getModuleList()
    {
        return $this->modules;
    }

    public function load()
    {
        if ( !$this->loaded )
        {
            $cache_enabled = (bool)get_option( 'zerobase_platform_cache', TRUE );
            //Load from Cache
            if ( $cache_enabled )
            {
                if ( $this->tryLoadCache() )
                {
                    $this->loaded = TRUE;

                    return TRUE;
                }
            }
            $this->loaded = TRUE;

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

        if ( !$this->post_types_loaded || !$this->taxonomies_loaded || !$this->metaboxes_loaded ||
             !$this->widgets_loaded || !$this->scripts_loaded
        )
        {
            return FALSE;
        }

        return TRUE;
    }

    private function loadCacheSegment( $cache )
    {
        if ( !isset( $this->loaded_segments[ $cache ] ) || $this->loaded_segments[ $cache ] === FALSE )
        {
            $cache_bag      = FileCache::getInstance()->createCache( 'config' );
            $loaded_objects = $cache_bag->retreive( 'cached_' . $cache );
            if ( $loaded_objects !== FALSE )
            {
                if ( !empty( $loaded_objects ) )
                {
                    foreach ( $loaded_objects as $cache_key )
                    {
                        $cache_bag = FileCache::getInstance()->createCache( $cache );
                        if ( $cache != 'widgets' || !class_exists( $cache_key ) )
                        {
                            $object = $cache_bag->retreive( $cache_key );
                            if ( $object === FALSE )
                            {
                                return FALSE;
                            }
                        }
                    }
                    $this->loaded_segments[ $cache ] = TRUE;

                    return TRUE;
                }

                return FALSE;
            }

            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    private function loadYamlFiles()
    {
        $cache_enabled = (bool)get_option( 'zerobase_platform_cache', TRUE );
        foreach ( $this->modules as $index => $config )
        {
            foreach ( $this->getYamlFilesFromDir( $config[ 'path' ], '.yml' ) as $file )
            {
                if ( strpos( $file, 'post_type' ) !== FALSE && !$this->post_types_loaded )
                {
                    $this->loadPostTypeFromYaml( $file, $cache_enabled );
                    $this->post_types_loaded = TRUE;
                }
                else if ( strpos( $file, 'taxonomy' ) !== FALSE && !$this->taxonomies_loaded )
                {
                    $this->loadTaxonomyFromYaml( $file, $cache_enabled );
                    $this->taxonomies_loaded = TRUE;
                }
                else if ( strpos( $file, 'metabox' ) !== FALSE && !$this->metaboxes_loaded )
                {
                    $this->loadMetaboxesFromYaml( $file, $cache_enabled );
                    $this->metaboxes_loaded = TRUE;
                }
                else if ( strpos( $file, 'widget' ) !== FALSE && !$this->widgets_loaded )
                {
                    $this->loadWidgetsFromYaml( $file, $cache_enabled );
                    $this->widgets_loaded = TRUE;
                }
                else if ( strpos( $file, 'script' ) !== FALSE && !$this->scripts_loaded )
                {
                    $this->loadScriptsFromYaml( $file, $cache_enabled );
                    $this->scripts_loaded = TRUE;
                }
            }
        }

        return TRUE;
    }

    private function loadPostTypeFromYaml( $file, $cache_enabled = TRUE )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result   = Yaml::parse( $file_contents );
        foreach ( $yaml_result as $post_type_name => $post_type_config )
        {
            if ( $post_type_name )
            {
                if ( !post_type_exists( $post_type_name ) )
                {
                    PostTypeImporter::load( $post_type_name, $post_type_config );
                    if ( $cache_enabled )
                    {
                        $cache_bag         = FileCache::getInstance()->createCache( 'config' );
                        $loaded_post_types = $cache_bag->retreive( 'cached_post_types' );
                        if ( $loaded_post_types === FALSE )
                        {
                            $loaded_post_types = array();
                        }
                        $loaded_post_types[] = $post_type_name;
                        $cache_bag->store( 'cached_post_types',
                            '<?php return ' . var_export( $loaded_post_types, TRUE ) . ' ?>' );
                    }
                }
            }
        }
    }

    private function loadTaxonomyFromYaml( $file, $cache_enabled = TRUE )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result   = Yaml::parse( $file_contents );
        foreach ( $yaml_result as $taxonomy_name => $taxonomy_config )
        {
            if ( $taxonomy_name )
            {
                if ( !taxonomy_exists( $taxonomy_name ) )
                {
                    TaxonomyImporter::load( $taxonomy_name, $taxonomy_config );
                    if ( $cache_enabled )
                    {
                        $cache_bag         = FileCache::getInstance()->createCache( 'config' );
                        $loaded_taxonomies = $cache_bag->retreive( 'cached_taxonomies' );
                        if ( $loaded_taxonomies === FALSE )
                        {
                            $loaded_taxonomies = array();
                        }
                        $loaded_taxonomies[] = $taxonomy_name;
                        $cache_bag->store( 'cached_taxonomies',
                            '<?php return ' . var_export( $loaded_taxonomies, TRUE ) . ' ?>' );
                    }
                }
            }
        }
    }

    private function loadMetaboxesFromYaml( $file, $cache_enabled = TRUE )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result   = Yaml::parse( $file_contents );
        foreach ( $yaml_result as $metabox_name => $metabox_config )
        {
            if ( $metabox_name )
            {
                MetaboxImporter::load( $metabox_name, $metabox_config );
                if ( $cache_enabled )
                {
                    $cache_bag        = FileCache::getInstance()->createCache( 'config' );
                    $loaded_metaboxes = $cache_bag->retreive( 'cached_metaboxes' );
                    if ( $loaded_metaboxes === FALSE )
                    {
                        $loaded_metaboxes = array();
                    }
                    $loaded_metaboxes[] = $metabox_name;
                    $cache_bag->store( 'cached_metaboxes',
                        '<?php return ' . var_export( $loaded_metaboxes, TRUE ) . ' ?>' );
                }
            }
        }
    }

    private function loadWidgetsFromYaml( $file, $cache_enabled = TRUE )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result   = Yaml::parse( $file_contents );
        foreach ( $yaml_result as $widget_name => $widget_config )
        {
            if ( $widget_name )
            {
                $widget_config[ 'base_path' ] = plugin_dir_path( $file );
                WidgetImporter::load( $widget_name, $widget_config );
                if ( $cache_enabled )
                {
                    $cache_bag      = FileCache::getInstance()->createCache( 'config' );
                    $loaded_widgets = $cache_bag->retreive( 'cached_widgets' );
                    if ( $loaded_widgets === FALSE )
                    {
                        $loaded_widgets = array();
                    }
                    $loaded_widgets[] = $widget_name;
                    $cache_bag->store( 'cached_widgets',
                        '<?php return ' . var_export( $loaded_widgets, TRUE ) . ' ?>' );
                }
            }
        }
    }

    private function loadScriptsFromYaml( $file, $cache_enabled = TRUE )
    {
        $file_contents = file_get_contents( $file );
        $yaml_result   = Yaml::parse( $file_contents );
        if ( $cache_enabled )
        {
            $cache_bag = FileCache::getInstance()->createCache( 'config' );
            ScriptsImporter::load( $file, $yaml_result );
            $loaded_scripts = $cache_bag->retreive( 'cached_scriptss' );
            if ( $loaded_scripts === FALSE )
            {
                $loaded_scripts = array();
            }
            $loaded_scripts[] = $file;
            $cache_bag->store( 'cached_scriptss', '<?php return ' . var_export( $loaded_scripts, TRUE ) . ' ?>' );
        }
    }

    private function getYamlFilesFromDir( $path, $mask = '.yml' )
    {
        $post_types = array();
        foreach ( scandir( $path ) as $file )
        {
            if ( strpos( $file, $mask ) !== FALSE )
            {
                $post_types[] = $path . '/' . $file;
            }
        }

        return $post_types;
    }
}
