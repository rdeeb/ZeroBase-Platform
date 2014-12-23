<?php
/**
 * ZB_BaseWidget
 * Defines a base way to generate widgets
 *
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 */

abstract class ZB_BaseWidget extends WP_Widget
{
    /**
     * Builds the widget base info
     **/
    public function __construct()
    {
        parent::__construct(
            get_class( $this ),
            $this->getName(),
            array(
                'class_name'  => get_class( $this ),
                'description' => $this->getDescription()
            )
        );
    }

    /**
     * widget Prints the widget, requires the getTemplate function
     *
     * @param $args     array Arguments
     * @param $instance array The data of this instance
     *
     * @return void
     **/
    public function widget( $args, $instance )
    {
        extract( $args );
        foreach ( $instance as $name => $value )
        {
            $instance[$name] = $this->sanitizeField( $name, $value );
        }
        extract( $instance );
        include( $this->getTemplate() );
    }

    /**
     * update Update the info of this widget instance
     *
     * @param $new_instance array New Values
     * @param $old_instance array Old values
     *
     * @return array
     **/
    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        foreach ( $new_instance as $name => $value )
        {
            $instance[$name] = $this->sanitizeField( $name, $value );
        }

        return $instance;
    }

    /**
     * form Displays the widget option form
     *
     * @param $instance array Instance values
     *
     * @return void
     **/
    public function form( $instance )
    {
        $form = $this->buildForm( $instance );
        $renderer = $form->getRenderer();
        echo $renderer->render();
    }

    /**
     * widgetFields Get the widget fields based on the configuration
     *
     * @return array
     **/
    private function widgetFields()
    {
        $fields  = $this->getFields();
        $ret_arr = array();
        foreach ( $fields as $name => $options )
        {
            $ret_arr[$name] = array_merge( array(
                'type'    => 'text',
                'default' => ''
            ), $options );
        }

        return $ret_arr;
    }

    /**
     * sanitizeField Returns a sanitized value based on the field options
     *
     * @param $field string New Values
     * @param $value mixed Old values
     *
     * @return mixed
     **/
    private function sanitizeField( $field, $value )
    {
        $fields = $this->widgetFields();
        switch ( $fields[$field]['type'] )
        {
            case 'checkbox':
                $value = (bool) $value;
                break;
            default:
                $value = strip_tags( $value );
                break;
        }
        return $value;
    }

    /**
     * buildForm Builds the form based on the configuration
     *
     * @return ZB_Form
     **/
    private function buildForm( $instance )
    {
        $fields = $this->widgetFields();
        $form   = ZB_FormFactory::createForm(get_class( $this ), 'widget');
        foreach ( $fields as $name => $options )
        {
            $args = array(
                'id'   => $this->get_field_id( $name ),
                'name' => $this->get_field_name( $name )
            );
            $form->addWidget( $name, $options['type'], $args, isset($instance[$name]) ? $instance[$name] : null );
        }

        return $form;
    }
}