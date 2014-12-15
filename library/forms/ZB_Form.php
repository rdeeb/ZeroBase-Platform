<?php

/**
 * ZB_Form
 * A class that builds options forms for Wordpress
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class ZB_Form
{
    //To store the form groups
    protected $widgets;

    public function __construct( $form_name )
    {
        $this->form_name = $form_name;
        $this->widgets   = array();
        //Load any default values
        if ( isset( $_REQUEST[$form_name] ) )
        {
            $this->values = $_REQUEST[$form_name];
        }
        else
        {
            $this->values = array();
        }

    }

    public function addWidget( $name, $type, array $options = array(), $default = NULL )
    {
        if ( !isset( $options['attr'] ) )
        {
            $options['attr'] = array();
        }
        $options['attr']['id']   = isset( $options['id'] ) ? $options['id'] : "{$this->form_name}_$name";
        $options['attr']['name'] = isset( $options['name'] ) ? $options['name'] : "{$this->form_name}[$name]";
        if ( !isset( $options['label'] ) )
        {
            $options['label'] = ucfirst( str_replace( '_', ' ', $name ) );
        }
        $wm = ZB_WidgetFactory::getInstance();
        $widget = $wm->createInstance($type, $options);
        if ( isset( $this->values[$name] ) )
        {
            $widget->setValue( $this->values[$name] );
        }
        else
        {
            if ( $default != NULL && !( isset( $_POST[$this->form_name] ) && $type == 'checkbox' ) )
            {
                $widget->setValue( $default );
                $this->values[$name] = $default;
            }
            else
            {
                if ( isset( $_POST[$this->form_name] ) && $type == 'checkbox' )
                {
                    $this->values[$name] = false;
                }
            }
        }
        $this->widgets[$name] = $widget;
    }

    public function render()
    {
        $str = '';
        foreach ( $this->widgets as $name => $widget )
        {
            $str .= $this->renderRow( $name ) . "\n";
        }

        return ZB_HtmlToolkit::buildTag( 'div', array(
            'id'    => "form_container_{$this->form_name}",
            'class' => 'form_container'
        ), false, $str );
    }

    public function renderRow( $name )
    {
        $params = $this->widgets[$name]->getParams();
        $widget = $this->renderWidget( $name );
        if ( isset( $params['desc'] ) && $params['desc'] )
        {
            $widget .= ZB_HtmlToolkit::buildTag( 'p', array(
                'class' => 'description'
            ), false, $params['desc'] );
        }
        $input_class = 'input';
        if ( isset( $params['prepend'] ) )
        {
            $input_class .= ' prepend';
        }
        if ( isset( $params['append'] ) )
        {
            $input_class .= ' append';
        }

        return ZB_HtmlToolkit::buildTag( 'div', array(
            'class' => 'form_row'
        ), false, $content = ZB_HtmlToolkit::buildTag( 'div', array(
                'class' => 'label'
            ), false, $this->renderLabel( $name ) ) . "\n" . ZB_HtmlToolkit::buildTag( 'div', array(
                'class' => $input_class
            ), false, $widget ) );
    }

    public function renderLabel( $name )
    {
        $params = $this->widgets[$name]->getParams();

        return ZB_HtmlToolkit::buildTag( 'label', array(
            'for' => "{$this->form_name}[$name]"
        ), false, $params['label'] );
    }

    public function renderWidget( $name )
    {
        return $this->widgets[$name]->render();
    }

    public function getValues()
    {
        return $this->values;
    }
} // END class FormBuilder