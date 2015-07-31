<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

class InputGalleryWidget extends BaseInputWidget
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
        $base_widget         = HtmlToolkit::buildTag( 'input', $this->attr, true ) . HtmlToolkit::buildTag( 'button', array( 'type' => 'button', 'class' => 'button action gallery' ), false, __( 'Select a gallery', 'zerobase' ) );
        $contents            = '';
        if ( $this->getValue() )
        {
            $remove_link = HtmlToolkit::buildTag( 'a', array( 'href' => '#', 'class' => 'delete' ), false, __( 'Delete' ) );
            foreach ( explode( ',', $this->getValue() ) as $id )
            {
                $contents .= HtmlToolkit::buildTag( 'li', array( 'class' => 'image', 'data-attachment_id' => $id ), false, wp_get_attachment_image( $id, array( 60, 60 ), false, array() ) . $remove_link );
            }
        }

        return HtmlToolkit::buildTag( 'ul', array( 'class' => 'gallery-preview' ), false, $contents ) . $base_widget;
    }
}
