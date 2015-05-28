<?php

/**
 * Autoloader for YML post types
 */

class ZB_ModuleLoader extends ZB_Singleton {
    protected $modules = array();

    public function addModule( $name, array $module_config ) {
        $this->modules[$name] = $module_config;
    }

    public function load() {
        foreach( $this->modules as $index => $config ) {
            $this->loadPostTypes( $config );
            $this->loadScripts( $config );
            $this->loadMetaBoxes( $config );
            $this->modules[$index] = $config;
        }
    }

    public function enqueue() {
        foreach( $this->modules as $index => $config ) {
            $this->enqueueScripts( $config );
        }
    }

    private function loadPostTypes( array &$config ) {
        foreach ( $this->getYamlFromDir( $config['path'], '.post_type.yml' ) as $file ) {
            $file_contents = file_get_contents( $file );
            $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
            $config['post_types'] = array();
            foreach ( $yaml_result as $post_type_name => $post_type_config ) {
                if ( $post_type_name ) {
                    $object = new ZB_BasePostType( $post_type_name, $post_type_config );
                    $object->register();
                    $config['post_types'][] = $object;
                }
            }
        }
    }

    private function loadMetaBoxes( array &$config ) {
        foreach ( $this->getYamlFromDir( $config['path'], '.metabox.yml' ) as $file ) {
            $file_contents = file_get_contents( $file );
            $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
            $config['metaboxes'] = array();
            foreach ( $yaml_result as $metabox_name => $metabox_config ) {
                if ( $metabox_name ) {
                    $metabox_config['id'] = $metabox_name;
                    $object = new ZB_Metabox( $metabox_config );
                    $config['metaboxes'][] = $object;
                }
            }
        }
    }

    private function loadTaxonomies() {
        foreach ( $this->getYamlFromDir( '.taxonomy.yml' ) as $file ) {
            $file_contents = file_get_contents( $file );
            $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
            foreach ( $yaml_result as $post_type_name => $post_type_config ) {

                if ( $post_type_name ) {

                }
            }
        }
    }

    private function loadScripts( array &$config ) {
        foreach ( $this->getYamlFromDir( $config['path'], '.script.yml' ) as $file ) {
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

    private function getYamlFromDir( $path, $mask = '.yml' ) {
        $post_types = array();
        foreach( scandir( $path ) as $file ) {
            if ( strpos( $file, $mask ) !== false ) {
                $post_types[] = $path . '/' . $file;
            }
        }
        return $post_types;
    }
}
