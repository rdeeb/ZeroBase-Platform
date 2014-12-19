<?php
require_once( __DIR__ . '/BaseWidget.php' );

/**
 * InputDateWidget
 * Renders a date input
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputDateWidget extends BaseWidget
{
    /**
     * getType
     * Returns this widget type
     *
     * @return string
     * @author Ramy Deeb
     **/
    public function getType()
    {
        return 'date';
    }

    /**
     * render
     * Returns the tag html code
     *
     * @return array
     * @author Ramy Deeb
     **/
    public function renderWidget()
    {
        if ( isset( $this->params['required'] ) && $this->params['required'] )
        {
            $this->attr['required'] = 'required';
        }
        $this->attr['value']           = $this->getValue();
        $this->attr['type']            = 'text';
        $this->attr['data-dateFormat'] = get_option( 'date_format' );
        $this->attr['class'] .= ' datepicker';

        return ZB_HtmlToolkit::buildTag( 'input', $this->attr, true );
    }
}