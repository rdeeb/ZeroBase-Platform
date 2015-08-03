<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

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
        foreach ( $this->params[ 'choices' ] as $key => $name )
        {
            if ( $this->getValue() == $key )
            {
                $content .= HtmlToolkit::buildTag( 'option', array(
                    'value'    => $key,
                    'selected' => 'selected'
                ), FALSE, $name );
            }
            else
            {
                $content .= HtmlToolkit::buildTag( 'option', array(
                    'value' => $key
                ), FALSE, $name );
            }
        }

        return HtmlToolkit::buildTag( 'select', $this->attr, FALSE, $content );
    }
}
