<?php
require_once(__DIR__ . '/ZB_BaseInputWidget.php');

/**
 * InputSelectWidget
 * Renders a select dropdown
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputSelectWidget extends ZB_BaseInputWidget
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
        return 'select';
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
        $content = '';
        foreach ( $this->params['choices'] as $key => $name )
        {
            if ( $this->getValue() == $key )
            {
                $content .= ZB_HtmlToolkit::buildTag( 'option', array(
                    'value'    => $key,
                    'selected' => 'selected'
                ), false, $name );
            }
            else
            {
                $content .= ZB_HtmlToolkit::buildTag( 'option', array(
                    'value' => $key
                ), false, $name );
            }
        }

        return ZB_HtmlToolkit::buildTag( 'select', $this->attr, false, $content );
    }
}