<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

abstract class BaseInputWidget implements WidgetInterface
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
     *
     * @author Ramy Deeb
     **/
    public function __construct( array $options = array() )
    {
        if ( $this->validateParams( $options ) )
        {
            if ( isset( $options[ 'attr' ] ) && is_array( $options[ 'attr' ] ) )
            {
                $this->attr = $options[ 'attr' ];
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

    public function setAttr( $name, $value )
    {
        $this->attr[ $name ] = $value;
    }

    public function getAttr( $name )
    {
        return $this->attr[ $name ];
    }

    /**
     * @param mixed $v
     *
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

    protected function supportedParams()
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
     *
     * @return bool
     * @throws \Exception
     */
    protected function validateParams( array $params )
    {
        $supported = $this->supportedParams();
        foreach ( $params as $key => $trash )
        {
            if ( !in_array( $key, $supported, TRUE ) && $key != 'attr' )
            {
                throw new \Exception( "The parameter $key is not supported by this widget" );
            }
        }

        return TRUE;
    }

    public function setErrors( $v )
    {
        $this->errors = $v;
    }

    public function hasErrors()
    {
        return !empty( $this->errors );
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
        if ( isset( $this->params[ 'append' ] ) && $this->params[ 'append' ] )
        {
            $widget .= '<span class="append">' . $this->params[ 'append' ] . '</span>';
        }
        if ( isset( $this->params[ 'prepend' ] ) && $this->params[ 'prepend' ] )
        {
            $widget = '<span class="prepend">' . $this->params[ 'prepend' ] . '</span>' . $widget;
        }

        return $widget;
    }

    /**
     * @return string
     */
    public function renderLabel()
    {
        return HtmlToolkit::buildLabel(
            $this->params[ 'label' ],
            array(
                'for' => isset( $this->params[ 'name' ] ) ? $this->params[ 'name' ] : ''
            )
        );
    }

    /**
     * @return string
     */
    public function renderErrors()
    {
        $liTags = '';
        foreach ( $this->errors as $error )
        {
            $liTags .= HtmlToolkit::buildTag( 'li', array(), FALSE, $error ) . "\n";
        }

        return HtmlToolkit::buildTag( 'ul', array(
            'class' => 'errors'
        ), FALSE, $liTags );
    }
}
