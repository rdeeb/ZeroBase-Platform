<?php

/**
 * Autoloader for YML post types
 */

class ZB_PostTypeLoader {
    protected $path;

    public function __construct( $path ) {
        if ( is_dir( $path ) ) {
            $this->path = $path;
        } else {
            throw new Exception( "The path \"$path\" is not a valid directory" );
        }
    }

    public function load() {
        foreach ( $this->getPostTypesFromDir() as $file ) {
            $file_contents = file_get_contents( $file );
            $yaml_result = \Symfony\Component\Yaml\Yaml::parse($file_contents);
            foreach ( $yaml_result as $post_type_name => $post_type_config ) {
                if ( $post_type_name ) {
                    $object = new ZB_BasePostType( $post_type_name, $post_type_config );
                    $object->register();
                }
            }
        }
    }

    private function getPostTypesFromDir() {
        $post_types = array();
        foreach( scandir( $this->path ) as $file ) {
            if ( strpos( $file, '.post_type.yml' ) !== false ) {
                $post_types[] = $this->path . '/' . $file;
            }
        }
        return $post_types;
    }
}