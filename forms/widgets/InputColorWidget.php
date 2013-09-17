<?php
require_once(__DIR__.'/BaseWidget.php');

/**
 * InputColorWidget
 * Renders a text input box that will work as a colorpicker
 *
 * @package ZeroBase
 * @author Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputColorWidget extends BaseWidget
{
    /**
     * getType
     * Returns this widget type
     *
     * @return string
     * @author Ramy Deeb
     **/
    public function getType ()
    {
        return 'color';
    }
    
    /**
     * render
     * Returns the tag html code
     *
     * @return array
     * @author Ramy Deeb
     **/
    public function renderWidget ()
    {
        if (isset($this->params['required']) && $this->params['required']) 
        {
            $this->attr['required'] = 'required';
        }
        $this->attr['value'] = $this->getValue();
        $this->attr['type'] = 'text';
        $this->attr['class'] .= ' colorselector';
        return zerobase_html_toolkit::buildTag( 'input', $this->attr, true );
    }
} // END class InputColorWidget