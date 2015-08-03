<?php
namespace Zerobase\Taxonomy;

use Zerobase\Forms\FormFactory;

class TaxonomyExtender
{
    //Variables
    private $taxonomyName;
    private $taxonomyFields;

    /**
     * @param string $taxonomy
     **/
    public function __construct( $taxonomy, $fields = NULL )
    {
        $this->taxonomyName   = $taxonomy;
        $this->taxonomyFields = array();
        if ( is_array( $fields ) && !empty( $fields ) )
        {
            foreach ( $fields as $name => $options )
            {
                $this->addField( $name, $options );
            }
        }
    }

    /**
     * @param string $name
     * @param array  $options
     */
    public function addField( $name, $options )
    {
        $options                       = array_merge( array(
            'type'    => 'text',
            'default' => '',
            'options' => array(),
            'single'  => TRUE
        ), $options );
        $this->taxonomyFields[ $name ] = $options;
    }

    /**
     * @param string $term
     */
    public function showFields( $term )
    {
        $term_meta = array();
        if ( is_object( $term ) )
        {
            foreach ( $this->taxonomyFields as $name => $options )
            {
                $term_meta[ $name ] = get_metadata( 'zerobase_term', $term->term_id, $name, $options[ 'single' ] );
            }
        }
        $project = $this->getForm( $term_meta );
        echo $project->getRenderer()->render();
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
            foreach ( $this->taxonomyFields as $name => $options )
            {
                $term_meta[ $name ] = get_metadata( 'zerobase_term', $term->term_id, $name, $options[ 'single' ] );
            }
        }
        $client = $this->getForm( $term_meta );
        echo $client->getRenderer()->render();
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
     * @return ZB_Form
     * @author Ramy Deeb
     **/
    private function getForm( $term = array() )
    {
        $object = FormFactory::createForm( $this->taxonomyName, 'Taxonomy' );
        foreach ( $this->taxonomyFields as $name => $options )
        {
            $object->addWidget( $name, $options[ 'type' ], $options[ 'options' ],
                isset( $term[ $this->taxonomyName ] ) ? $term[ $this->taxonomyName ] : '' );
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
        add_action( $this->taxonomyName . '_add_form_fields', array(
            &$this,
            'showFields'
        ), 10, 2 );
        add_action( $this->taxonomyName . '_edit_form_fields', array(
            &$this,
            'editFields'
        ), 10, 2 );
        add_action( 'created_' . $this->taxonomyName, array(
            &$this,
            'fieldSave'
        ), 10, 2 );
        add_action( 'edited_' . $this->taxonomyName, array(
            &$this,
            'fieldSave'
        ), 10, 2 );
    }
}
