<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

/**
 * InputCheckboxWidget
 * Renders a check box input box
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputCheckboxWidget extends BaseInputWidget
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
        return 'checkbox';
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
        $this->attr['value'] = 1;
        if ( $this->getValue() === true || $this->getValue() == 1 )
        {
            $this->attr['checked'] = 'checked';
        }
        $this->attr['type'] = $this->getType();

        return HtmlToolkit::buildTag( 'input', $this->attr, true );
    }
}
