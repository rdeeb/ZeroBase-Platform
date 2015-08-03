<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

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
        if ( isset( $this->params[ 'required' ] ) && $this->params[ 'required' ] )
        {
            $this->attr[ 'required' ] = 'required';
        }
        $this->attr[ 'value' ] = 1;
        if ( $this->getValue() === TRUE || $this->getValue() == 1 )
        {
            $this->attr[ 'checked' ] = 'checked';
        }
        $this->attr[ 'type' ] = $this->getType();

        return HtmlToolkit::buildTag( 'input', $this->attr, TRUE );
    }
}
