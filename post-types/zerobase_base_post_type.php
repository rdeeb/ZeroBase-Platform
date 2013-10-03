<?php
/**
 * zerobase_base_post_type
 *
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 */

abstract class zerobase_base_post_type implements zerobase_post_type_interface
{
    /**
     * Configure
     * Makes the post type available in Wordpress
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    public function configure()
    {
        //Register the Post Type, taxonomies, and metaboxes (if any)
        $this->registerPostType();
        $this->registerTaxonomy();
        $this->registerMetaboxes();
        //Registers the hooks for the widgets and scripts (if any)
        add_action( 'widgets_init', array( &$this, 'registerWidgets' ) );
        add_action( 'wp_register_scripts', array( &$this, 'registerScripts' ) );
    }

    /**
     * getName
     * Returns the name of the post type
     *
     * @return string
     * @author Ramy Deeb
     **/
    public function getName()
    {
        throw new Exception( 'This function needs to be implemented' );
    }

    /**
     * getDescription
     * Returns the description of the post type
     *
     * @return string
     * @author Ramy Deeb
     **/
    public function getDescription()
    {
        throw new Exception( 'This function needs to be implemented' );
    }

    /**
     * getOptions
     * Returns the post type options
     *
     * @return array
     * @author Ramy Deeb
     **/
    public function getOptions()
    {
        throw new Exception( 'This function needs to be implemented' );
    }

    /**
     * registerPostType Registers the post type itself
     *
     * @return void
     * @author Ramy Deeb
     **/
    private function registerPostType()
    {
        return;
    }

    /**
     * registerTaxonomy Register the custom taxonomies
     *
     * @return void
     * @author Ramy Deeb
     **/
    private function registerTaxonomy()
    {
        return;
    }

    /**
     * registerMetaboxes Registers the custom metaboxes
     *
     * @return array
     * @author Ramy Deeb
     **/
    private function registerMetaboxes()
    {
        return;
    }

    /**
     * registerWidgets Register the post type widgets
     *
     * @return void
     * @author Ramy Deeb
     */
    public function registerWidgets()
    {
        return;
    }

    /**
     * registerScripts Registers the required scripts & styles for this post type
     *
     * @return void
     * @author Ramy Deeb
     */
    public function registerScripts()
    {
        return;
    }
} 