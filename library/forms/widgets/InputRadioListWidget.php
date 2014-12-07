<?php
require_once( __DIR__ . '/BaseWidget.php' );

/**
 * InputRadioListWidget
 * Renders a radio input box
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputRadioListWidget extends BaseWidget
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
        return 'radio';
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
        $this->attr['type'] = $this->getType();
        $content            = '';
        foreach ( $this->params['choices'] as $key => $label )
        {
            $attrs          = $this->attr;
            $attrs['value'] = $key;
            if ( $this->getValue() == $key )
            {
                $attrs['checked'] = 'checked';
            }

            $content .= ZB_HtmlToolkit::buildTag( 'p', array(), false, ZB_HtmlToolkit::buildTag( 'label', array( 'class' => 'inline-block' ), false, ZB_HtmlToolkit::buildTag( 'input', $attrs, true ) . " $label" ));
        }

        return $content;
    }
} // END class InputRadioListWidget