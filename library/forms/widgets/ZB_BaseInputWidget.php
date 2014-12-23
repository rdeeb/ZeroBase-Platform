<?php
require_once( __DIR__ . '/WidgetInterface.php' );

/**
 * ZB_BaseInputWidget
 * An abstract class that handles the base functionalities of a widget
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
abstract class ZB_BaseInputWidget implements WidgetInterface
{
    //Internal variables
    protected $value; //The value stored by the widget
    protected $attr; //The HTML attributes
    protected $params; //Any widgets params
    protected $errors;

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
     * @return array
     **/
    public function getParams()
    {
        return $this->params;
    }

    public function setAttr($name, $value)
    {
        $this->attr[$name] = $value;
    }

    public function getAttr($name)
    {
        return $this->attr[$name];
    }

    /**
     * @param mixed $v
     * @return void
     **/
    public function setValue( $v )
    {
        $this->value = $v;
    }

    /**
     * @return mixed
     **/
    public function getValue()
    {
        return $this->value;
    }

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
     * @param array $params
     * @return bool
     * @throws Exception
     */
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

    public function setErrors($v)
    {
        $this->errors = $v;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
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

    /**
     * @return string
     */
    public function renderLabel()
    {
        return ZB_HtmlToolkit::buildLabel(
            $this->params['label'],
            array(
                'for' => @$this->params['name']
            )
        );
    }

    /**
     * @return string
     */
    public function renderErrors()
    {
        $liTags = '';
        foreach ($this->errors as $error)
        {
            $liTags .= ZB_HtmlToolkit::buildTag('li', array(), false, $error)."\n";
        }
        return ZB_HtmlToolkit::buildTag('ul', array(
            'class' => 'errors'
        ), false, $liTags);
    }
}