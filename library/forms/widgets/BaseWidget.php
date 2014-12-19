<?php
require_once( __DIR__ . '/WidgetInterface.php' );

/**
 * BaseWidget
 * An abstract class that handles the base functionalities of a widget
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
abstract class BaseWidget implements WidgetInterface
{
    //Internal variables
    protected $value; //The value stored by the widget
    protected $attr; //The HTML attributes
    protected $params; //Any widgets params

    /**
     * Creates the widget with the options defined for it
     *
     * @param $options array
     * @author Ramy Deeb
     **/
    public function __construct( array $options = array() )
    {
        if ( $this->validateParams( $options ) )
        {
            if ( isset( $options['attr'] ) && is_array( $options['attr'] ) )
            {
                $this->attr = $options['attr'];
            }
            $this->params = $options;
        }
    }

    /**
     * getParams
     * Returns the parameters list
     *
     * @return array
     * @author Ramy Deeb
     **/
    public function getParams()
    {
        return $this->params;
    }

    /**
     * setValue
     * Sets the value for the widget
     *
     * @param $v mixed the value to set on the widget
     *
     * @return void
     * @author Ramy Deeb
     **/
    public function setValue( $v )
    {
        $this->value = $v;
    }

    /**
     * getValue
     * Returns the value of the widget
     *
     * @return mixed
     * @author Ramy Deeb
     **/
    public function getValue()
    {
        return $this->value;
    }

    /**
     * supportedParams
     * Returns the supported parameters by this widget
     *
     * @return array
     * @author Ramy Deeb
     **/
    private function supportedParams()
    {
        return array(
            'id',
            'name',
            'style',
            'required',
            'label',
            'desc',
            'append',
            'prepend',
            'choices'
        );
    }

    /**
     * validateParams
     * Validates if the params sent are supported by this widget
     *
     * @param $params array This are the params sent by the user
     *
     * @return bool
     * @author Ramy Deeb
     * @throws Exception
     **/
    private function validateParams( array $params )
    {
        $supported = $this->supportedParams();
        foreach ( $params as $key => $trash )
        {
            if ( !in_array( $key, $supported, true ) && $key != 'attr' )
            {
                throw new Exception( "The parameter $key is not supported by this widget" );
            }
        }

        return true;
    }

    /**
     * Renders the widget
     * @return string The widget HTML
     * @deprecated
     */
    public function render()
    {
        $widget = $this->renderWidget();
        if ( isset( $this->params['append'] ) && $this->params['append'] )
        {
            $widget .= '<span class="append">' . $this->params['append'] . '</span>';
        }
        if ( isset( $this->params['prepend'] ) && $this->params['prepend'] )
        {
            $widget = '<span class="prepend">' . $this->params['prepend'] . '</span>' . $widget;
        }

        return $widget;
    }

    public function renderLabel()
    {
        return ZB_HtmlToolkit::buildLabel(
            $this->params['label'],
            array(
                'for' => $this->params['name']
            )
        );
    }
}