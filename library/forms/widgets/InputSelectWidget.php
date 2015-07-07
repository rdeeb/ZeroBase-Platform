<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

/**
 * InputSelectWidget
 * Renders a select dropdown
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputSelectWidget extends BaseInputWidget
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
                $content .= HtmlToolkit::buildTag( 'option', array(
                    'value'    => $key,
                    'selected' => 'selected'
                ), false, $name );
            }
            else
            {
                $content .= HtmlToolkit::buildTag( 'option', array(
                    'value' => $key
                ), false, $name );
            }
        }

        return HtmlToolkit::buildTag( 'select', $this->attr, false, $content );
    }
}
