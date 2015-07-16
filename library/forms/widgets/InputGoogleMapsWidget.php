<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

/**
 * InputGoogleMapsWidget
 * Renders a Google Map selection widget
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputGoogleMapsWidget extends BaseInputWidget
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
        return 'text';
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
        $this->attr['value'] = trim($this->getValue(), '()');
        $this->attr['type']  = $this->getType();
        $this->attr['class'] = isset( $this->attr['class'] ) ? $this->attr['class'].' gmap-latlong' : 'gmap-latlong';

        $contents = HtmlToolkit::buildDiv( '', array(
            'class' => 'map-canvas'
        ) );
        $contents .= HtmlToolkit::buildTag( 'input', $this->attr, true );

        return HtmlToolkit::buildDiv( $contents ,array(
            'class' => 'map-selector'
        ));
    }
}
