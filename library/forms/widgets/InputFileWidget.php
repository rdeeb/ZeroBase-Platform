<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

class InputFileWidget extends BaseInputWidget
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
        return 'file';
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
        $base_widget         = HtmlToolkit::buildTag( 'input', $this->attr, true ) . HtmlToolkit::buildTag( 'button', array( 'type' => 'button', 'class' => 'button action uploader' ), false, __( 'Select File', 'zerobase' ) );
        if ( $this->getValue() )
        {
            return wp_get_attachment_image( $this->getValue(), array( 60, 60 ), true, array( 'class' => 'doc preview' ) ) . $base_widget . HtmlToolkit::buildTag( 'button', array( 'type' => 'button', 'class' => 'button delete' ), false, __( 'Remove File', 'zerobase' ) );
        }

        return $base_widget;
    }
}
