<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

/**
 * InputTextareaWidget
 * Renders a textarea input box
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputTextareaWidget extends BaseInputWidget
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
        return 'textarea';
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
        return sanitize_text_field( $this->value );
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

        return HtmlToolkit::buildTag( 'textarea', $this->attr, false, $this->getValue() );
    }
}
