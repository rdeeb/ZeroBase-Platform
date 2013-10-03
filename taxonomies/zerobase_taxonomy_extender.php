<?php
/**
 * zerobase_taxonomy_extender
 * Allows you to add new fields to a taxonomy
 *
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 */
class zerobase_taxonomy_extender
{
    //Variables
    private $tax_name;
    private $tax_fields;

    /**
     * __construct
     * Creates the taxonomy extender object
     *
     * @param $taxonomy string The taxonomy name to extend
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function __construct( $taxonomy, $fields = NULL )
    {
        $this->tax_name   = $taxonomy;
        $this->tax_fields = array();
        if ( is_array( $fields ) && !empty( $fields ) )
        {
            foreach ( $fields as $name => $options )
            {
                $this->addField( $name, $options );
            }
        }
    }

    /**
     * addField
     * Adds a new field to the taxonomy
     *
     * @param
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function addField( $name, $options )
    {
        $options = array_merge( $options, array(
            'type' => 'text'
            'default' => '',
            'options' => array(),
            'single' => true
        ) );
        $this->tax_fields[$name] = $options;
    }

    /**
     * showFields
     * Add new custom fields to the taxonomy
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function showFields( $term )
    {
        $term_meta = array();
        if ( is_object( $term ) )
        {
            foreach ( $this->tax_fields as $name => $options )
            {
                $term_meta[$name] = get_metadata( 'zerobase_term', $term->term_id, $name, $options['single'] );
            }
        }
        $project = $this->getForm( $term_meta );
        echo $project->render();
    }

    /**
     * editFields
     * Add new custom fields to the taxonomy
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function editFields( $term )
    {
        $term_meta = array();
        if ( is_object( $term ) )
        {
            foreach ( $this->tax_fields as $name => $options )
            {
                $term_meta[$name] = get_metadata( 'zerobase_term', $term->term_id, $name, $options['single'] );
            }
        }
        $client = $this->getForm( $term_meta );
        echo $client->renderTr();
    }

    /**
     * zerobase_taxonomy_portfolio_clients_fields_save
     * Saves the custom fields of the portfolio client taxonomy
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function fieldSave( $term_id )
    {
        $client = $this->getForm();
        foreach ( $client->getValues() as $key => $value )
        {
            update_metadata( 'zerobase_term', $term_id, $key, $value );
        }
    }

    /**
     * getForm
     * Creates the form to be used in the custom taxonomies fields
     *
     * @return zerobase_tax_form_builder
     * @author Ramy Deeb
     **/
    private function getForm( $term = array() )
    {
        $object = new zerobase_tax_form_builder( $this->tax_name );
        foreach ( $this->tax_fields as $name => $options )
        {
            $object->addWidget( $name, $options['type'], $options['options'], isset( $term[$this->tax_name] ) ? $term[$this->tax_name] : '' );
        }

        return $object;
    }

    /**
     * register
     * Registers the hooks for the taxonomy fields
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function register()
    {
        //Hook the custom fields for the taxonomy fields
        add_action( 'zerobase_' . $this->tax_name . '_add_form_fields', array( &$this, 'showFields' ), 10, 2 );
        add_action( 'zerobase_' . $this->tax_name . '_edit_form_fields', array( &$this, 'editFields' ), 10, 2 );
        add_action( 'created_' . $this->tax_name, array( &$this, 'fieldSave' ), 10, 2 );
        add_action( 'edited_' . $this->tax_name, array( &$this, 'fieldSave' ), 10, 2 );
    }
} // END class zerobase_taconomy_extender