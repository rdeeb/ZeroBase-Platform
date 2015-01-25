<?php

/**
 * ZB_Metabox
 * Generates custom metaboxes for your post, pages and custom post types
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class ZB_Metabox
{
    /**
     * @param array $options The options for building the custom meta box
     **/
    public function __construct( array $options )
    {
        $defaults = $this->getPostTypeDefaults();
        $this->options = array_merge( $defaults, $options );
        add_action( 'add_meta_boxes', array( &$this, 'registerMetaboxes' ) );
        add_action( 'save_post', array( &$this, 'saveMetaInfo' ), 10, 2 );
    }

    private function getPostTypeDefaults()
    {
        return array(
            'id'        => 'zb_metabox',
            'title'     => __( 'Zerobase Metabox', 'zerobase' ),
            'post_type' => array( 'post' ),
            'context'   => 'normal',
            'priority'  => 'default',
            'fields'    => array(),
            'template'  => 'default'
        );
    }

    public function registerMetaboxes()
    {
        foreach ( $this->options['post_type'] as $post_type )
        {
            if ( $post_type == $this->getCurrentPostType() )
            {
                add_meta_box(
                    $this->options['id'],
                    $this->options['title'],
                    array( &$this, 'renderMetaBox' ),
                    $post_type,
                    $this->options['context'],
                    $this->options['priority']
                );
            }
        }
    }

    /**
     * Saves the custom meta info for the post
     * @param int $post_id The Post ID
     * @return mixed
     **/
    public function saveMetaInfo( $post_id, $post )
    {
        $options = $this->options;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if ( !current_user_can( 'edit_post' ) )
        {
            return $post_id;
        }

        if ( in_array( $post->post_type, $options['post_type'] ) )
        {
            $form    = $this->getForm( $post_id );
            if ($form->isValid())
            {
                $form->save();
            }
        }
    }

    /**
     * Renders the custom meta box
     * @return void
     **/
    public function renderMetaBox( $post )
    {
        $form  = $this->getForm( $post->ID );
        $nonce = wp_nonce_field( $this->options['id'], 'ZB_Metabox' );
        switch ( $this->options['template'] )
        {
            case 'default':
            default:
                extract( $this->options );
                $renderer = $form->getRenderer();
                require( __DIR__ . '/templates/zerobase_metabox_default.php' );
                break;
        }
    }

    /**
     * Returns the ZB_Form object
     * @return ZB_Form
     **/
    private function getForm( $post_id )
    {
        $form     = ZB_FormFactory::createForm( $this->options['id'], 'default', 'metadata' );
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
     * gets the current post type in the WordPress Admin
     * @return mixed
     * @author http://themergency.com/wordpress-tip-get-post-type-in-admin/, Ramy Deeb
     */
    private function getCurrentPostType()
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
