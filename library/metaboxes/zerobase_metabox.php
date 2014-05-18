<?php

/**
 * zerobase_metabox
 * Generates custom metaboxes for your post, pages and custom post types
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class zerobase_metabox
{
    /**
     * __construct
     *
     * @param array $options The options for building the custom meta box
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function __construct( array $options )
    {
        $defaults      = array(
            'id'        => 'zb_metabox',
            'title'     => __( 'Zerobase Metabox', 'zerobase' ),
            'post_type' => array( 'post' ),
            'context'   => 'normal',
            'priority'  => 'default',
            'fields'    => array(),
            'template'  => 'default'
        );
        $this->options = array_merge( $defaults, $options );
        foreach ( $options['post_type'] as $post_type )
        {
            if ( $post_type == $this->get_current_post_type() )
            {
                add_meta_box(
                    $this->options['id'],
                    $this->options['title'],
                    array( &$this, 'render_meta_box' ),
                    $post_type,
                    $this->options['context'],
                    $this->options['priority']
                );
            }
        }
    }

    /**
     * save_meta_info
     * Saves the custom meta info for the post
     *
     * @param int $_post_id The Post ID
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function save_meta_info( $post_id, $object )
    {
        $options = $this->options;
        if ( in_array( $object->post_type, $options['post_type'] ) )
        {
            $form    = $this->get_form( $post_id );
            $values  = $form->getValues();
            $post_ID = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : $post_id;
            foreach ( $values as $key => $value )
            {
                update_post_meta( $post_ID, $key, $value );
            }
        }
    }

    /**
     * render_meta_box
     * Renders the custom meta box
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function render_meta_box( $post )
    {
        $form  = $this->get_form( $post->ID );
        $nonce = wp_nonce_field( $this->options['id'], 'zerobase_metabox' );
        switch ( $this->options['template'] )
        {
            case 'default':
            default:
                extract( $this->options );
                require( __DIR__ . '/templates/zerobase_metabox_default.php' );
                break;
        }
    }

    /**
     * get_form
     * Returns the zerobase_form_builder object
     *
     * @return zerobase_form_builder
     * @author Ramy Deeb
     **/
    private function get_form( $post_id )
    {
        $form     = new zerobase_form_builder( $this->options['id'] );
        $defaults = array(
            'type'    => 'text',
            'default' => NULL
        );
        foreach ( $this->options['fields'] as $name => $options )
        {
            $options = array_merge( $defaults, $options );
            $type    = $options['type'];
            $default = $options['default'];
            $value   = get_post_meta( $post_id, $name, true );
            $value   = $value ? $value : $default;
            unset(
            $options['type'],
            $options['default']
            );
            $form->addWidget( $name, $type, $options, $value );
        }

        return $form;
    }

    /**
     * get_current_post_type
     * gets the current post type in the WordPress Admin
     *
     * @return mixed
     * @author http://themergency.com/wordpress-tip-get-post-type-in-admin/, Ramy Deeb
     */
    private function get_current_post_type()
    {
        global $post, $typenow, $current_screen;

        //we have a post so we can just get the post type from that
        if ( $post && $post->post_type )
        {
            return $post->post_type;
        }

        //check the global $typenow - set in admin.php
        elseif ( $typenow )
        {
            return $typenow;
        }

        //check the global $current_screen object - set in sceen.php
        elseif ( $current_screen && $current_screen->post_type )
        {
            return $current_screen->post_type;
        }

        //lastly check the post_type querystring
        elseif ( isset( $_REQUEST['post_type'] ) )
        {
            return sanitize_key( $_REQUEST['post_type'] );
        }

        else
        {
            if ( isset( $_REQUEST['post'] ) )
            {
                return get_post_type( $_REQUEST['post'] );
            }

            else
            {
                if ( isset( $_REQUEST['post_ID'] ) )
                {
                    return get_post_type( $_REQUEST['post_ID'] );
                }
            }
        }

        //we do not know the post type!
        return NULL;
    }
}