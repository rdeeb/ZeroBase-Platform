<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

class InputRadioListWidget extends BaseInputWidget
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
        return 'radio';
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
        $this->attr['type'] = $this->getType();
        $content            = '';
        foreach ( $this->params['choices'] as $key => $label )
        {
            $attrs          = $this->attr;
            $attrs['value'] = $key;
            if ( $this->getValue() == $key )
            {
                $attrs['checked'] = 'checked';
            }

            $content .= HtmlToolkit::buildTag( 'p', array(), false, HtmlToolkit::buildTag( 'label', array( 'class' => 'inline-block' ), false, HtmlToolkit::buildTag( 'input', $attrs, true ) . " $label" ));
        }

        return $content;
    }
}
