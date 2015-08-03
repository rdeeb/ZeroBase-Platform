<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

class InputHiddenWidget extends BaseInputWidget
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
        return 'hidden';
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
        $this->attr[ 'value' ] = $this->getValue();
        $this->attr[ 'type' ]  = $this->getType();

        return HtmlToolkit::buildTag( 'input', $this->attr, TRUE );
    }
}
