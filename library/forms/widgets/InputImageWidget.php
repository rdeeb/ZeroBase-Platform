<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

/**
 * InputImageWidget
 * Renders a text input box
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputImageWidget extends BaseInputWidget
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
        return 'image';
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
        $this->attr['value'] = $this->getValue();
        $this->attr['type']  = 'hidden';
        $base_widget         = HtmlToolkit::buildTag( 'input', $this->attr, true ) . HtmlToolkit::buildTag( 'button', array( 'type' => 'button', 'class' => 'button action image_selector' ), false, __( 'Select File', 'zerobase' ) );
        if ( $this->getValue() )
        {
            return wp_get_attachment_image( $this->getValue(), array( 60, 60 ), false, array( 'class' => 'img preview' ) ) . $base_widget . HtmlToolkit::buildTag( 'button', array( 'type' => 'button', 'class' => 'button delete' ), false, __( 'Remove File', 'zerobase' ) );
        }

        return $base_widget;
    }
}
