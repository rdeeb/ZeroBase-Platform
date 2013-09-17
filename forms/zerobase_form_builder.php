<?php
require_once(__DIR__.'/widgets/InputHiddenWidget.php');
require_once(__DIR__.'/widgets/InputTextWidget.php');
require_once(__DIR__.'/widgets/InputTextareaWidget.php');
require_once(__DIR__.'/widgets/InputCheckboxWidget.php');
require_once(__DIR__.'/widgets/InputCheckboxListWidget.php');
require_once(__DIR__.'/widgets/InputRadioListWidget.php');
require_once(__DIR__.'/widgets/InputSelectWidget.php');
require_once(__DIR__.'/widgets/InputDateWidget.php');
require_once(__DIR__.'/widgets/InputColorWidget.php');
require_once(__DIR__.'/widgets/InputImageWidget.php');
require_once(__DIR__.'/widgets/InputFileWidget.php');
require_once(__DIR__.'/widgets/InputGalleryWidget.php');

/**
 * zerobase_form_builder
 * A class that builds options forms for Wordpress
 *
 * @package ZeroBase
 * @author Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class zerobase_form_builder
{
    //To store the form groups
    protected $widgets;
    
    public function __construct ( $form_name )
    {
        $this->form_name = $form_name;
        $this->widgets = array();
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
    
    public function addWidget ( $name, $type, array $options = array(), $default = null )
    {
        $widget = null;
        if ( !isset( $options['attr'] ) )
        {
            $options['attr'] = array();
        }
        $options['attr']['id'] = isset( $options['id'] ) ? $options['id'] : "{$this->form_name}_$name";
        $options['attr']['name'] = isset( $options['name'] ) ? $options['name'] : "{$this->form_name}[$name]";
        if ( !isset( $options['label'] ) )
        {
            $options['label'] = ucfirst( str_replace( '_', ' ', $name ) );
        }
        switch ($type)
        {
            case 'text':
                $widget = new InputTextWidget( $options );
                break;
            case 'textarea':
                $widget = new InputTextareaWidget( $options );
                break;
            case 'hidden':
                $widget = new InputHiddenWidget( $options );
                break;
            case 'checkbox':
                $widget = new InputCheckboxWidget( $options );
                break;
            case 'checkbox_list':
                $widget = new InputCheckboxListWidget( $options );
                break;
            case 'radio_list':
                $widget = new InputRadioListWidget( $options );
                break;
            case 'select':
                $widget = new InputSelectWidget( $options );
                break;
            case 'date':
                $widget = new InputDateWidget( $options );
                break;
            case 'colorpicker':
                $widget = new InputColorWidget( $options );
                break;
            case 'file':
                $widget = new InputFileWidget( $options );
                break;
            case 'image':
                $widget = new InputImageWidget( $options );
                break;
            case 'gallery':
                $widget = new InputGalleryWidget( $options );
                break;
            default:
                throw new Exception( "The widget type $type is not defined" );
                break;
        }
        if ( isset( $this->values[$name] ) )
        {
            $widget->setValue($this->values[$name]);
        }
        else if ( $default != null && !( isset( $_POST[$this->form_name] ) && $type == 'checkbox' ) )
        {
            $widget->setValue($default);
            $this->values[$name] = $default;
        }
        else if ( isset( $_POST[$this->form_name] ) && $type == 'checkbox' )
        {
            $this->values[$name] = false;
        }
        $this->widgets[$name] = $widget;
    }
    
    public function render ()
    {
        $str = '';
        foreach ( $this->widgets as $name => $widget )
        {
            $str .= $this->renderRow( $name )."\n";
        }
        return zerobase_html_toolkit::buildTag( 'div', array(
            'id' => "form_container_{$this->form_name}",
            'class' => 'form_container'
        ), false, $str);
    }
    
    public function renderRow ( $name )
    {
        $params = $this->widgets[$name]->getParams();
        $widget = $this->renderWidget( $name );
        if ( isset($params['desc']) && $params['desc'] )
        {
            $widget .= zerobase_html_toolkit::buildTag( 'p', array(
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
        return zerobase_html_toolkit::buildTag( 'div', array(
                'class' => 'form_row'
            ), false, $content = zerobase_html_toolkit::buildTag( 'div', array(
                'class' => 'label'
            ), false, $this->renderLabel( $name ) )."\n".    zerobase_html_toolkit::buildTag( 'div', array(
                'class' => $input_class
            ), false, $widget ) );
    }
    
    public function renderLabel ( $name )
    {
        $params = $this->widgets[$name]->getParams();
        return zerobase_html_toolkit::buildTag( 'label', array(
            'for' => "{$this->form_name}[$name]"
        ), false, $params['label'] );
    }
    
    public function renderWidget ( $name )
    {
        return $this->widgets[$name]->render();
    }
    
    public function getValues()
    {
        return $this->values;
    }
} // END class FormBuilder