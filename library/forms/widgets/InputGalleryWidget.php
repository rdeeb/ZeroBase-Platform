<?php
require_once( __DIR__ . '/BaseWidget.php' );

/**
 * InputGalleryWidget
 * Renders a gallery selector
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class InputGalleryWidget extends BaseWidget
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
        return 'gallery';
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
        return rtrim( $this->value, ',' );
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
        $base_widget         = ZB_HtmlToolkit::buildTag( 'input', $this->attr, true ) . ZB_HtmlToolkit::buildTag( 'button', array( 'type' => 'button', 'class' => 'button action gallery' ), false, __( 'Select a gallery', 'zerobase' ) );
        $contents            = '';
        if ( $this->getValue() )
        {
            $remove_link = ZB_HtmlToolkit::buildTag( 'a', array( 'href' => '#', 'class' => 'delete' ), false, __( 'Delete' ) );
            foreach ( explode( ',', $this->getValue() ) as $id )
            {
                $contents .= ZB_HtmlToolkit::buildTag( 'li', array( 'class' => 'image', 'data-attachment_id' => $id ), false, wp_get_attachment_image( $id, array( 60, 60 ), false, array() ) . $remove_link );
            }
        }

        return ZB_HtmlToolkit::buildTag( 'ul', array( 'class' => 'gallery-preview' ), false, $contents ) . $base_widget;
    }
}