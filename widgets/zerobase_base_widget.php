<?php
/**
 * zerobase_base_widget
 * Defines a base way to generate widgets
 *
 * @author Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 */

abstract class zerobase_base_widget extends WP_Widget
{
    /**
     * __construct Builds the widget base info
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    function ();
    public function __construct()
    {
        parent::__construct(
            get_class($this),
            $this->getName(),
            array(
                'class_name' => get_class($this),
                'description' => $this->getDescription()
            )
        );
    }

    /**
     * widget Prints the widget, requires the getTemplate function
     *
     * @param $args array Arguments
     * @param $instance array The data of this instance
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    public function widget( $args, $instance )
    {
        extract( $args );
        foreach ( $instance as $name => $value )
        {
            $instance[$name] = $this->sanitizeField( $name, $value )
        }
        extract( $instance );
        include( $this->getTemplate() );
    }

    /**
     * update Update the info of this widget instance
     *
     * @param $new_instance array New Values
     * @param $old_instance array Old values
     * @return array
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        foreach ( $new_instance as $name => $value )
        {
            $instance[$name] = $this->sanitizeField( $name, $value )
        }
        return $instance;
    }

    /**
     * form Displays the widget option form
     *
     * @param $instance array Instance values
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    public function form( $instance )
    {
        $form = $this->buildForm();
        echo $form->render();
    }

    /**
     * widgetFields Get the widget fields based on the configuration
     *
     * @return array
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function widgetFields()
    {
        $fields = $this->getFields();
        $ret_arr = array();
        foreach( $fields as $name => $options )
        {
            $ret_arr[$name] = array_merge( $options, array(
                'type' => 'text',
                'default' => ''
            ) );
        }
        return $ret_arr;
    }

    /**
     * sanitizeField Returns a sanitized value based on the field options
     *
     * @param $field string New Values
     * @param $value mixed Old values
     * @return mixed
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function sanitizeField( $field, $value )
    {
        $fields = $this->widgetFields();
        switch( $fields[$field]['type'] )
        {
            case 'checkbox':
                $value = (bool) $value;
                break;
            default:
                $value = strip_tags( $value )
                break;
        }
    }

    /**
     * buildForm Builds the form based on the configuration
     *
     * @return zerobase_widget_form_builder
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function buildForm()
    {
        $fields = $this->widgetFields();
        $form = new zerobase_widget_form_builder( get_class($this) );
        foreach ($fields as $name => $options)
        {
            $args = array(
                'id' => $this->get_field_id( $name ),
                'name' => $this->get_field_name( $name )
            );
            if ( $args['label'] )
            {
                $args['label'] = __( 'Show logo?', 'zerobase' );
            }
            $form->addWidget( $name, $options['type'], $args, $options['default'] );
        }
        return $form;
    }

    /**
     * getName Returns this widget name
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function getName()
    {
        throw new Exception( 'This should return the name of the widget' );
    }

    /**
     * getDescription Returns this widget description
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function getDescription()
    {
        throw new Exception( 'This should return the widget description' );
    }

    /**
     * getFields Returns this widget fields
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function getFields()
    {
        throw new Exception( 'This should return an array of fields' );
    }

    /**
     * getTemplate Returns this widget template path
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function getTemplate()
    {
        throw new Exception( 'This should return an string with the path of the template' );
    }
}