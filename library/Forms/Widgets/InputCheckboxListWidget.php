<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

class InputCheckboxListWidget extends BaseInputWidget
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
        return 'checkbox_list';
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
        $id       = $this->attr['id'];
        $name     = $this->attr['name'];
        $contents = '';
        $values   = $this->getValue();
        foreach ( $this->params['choices'] as $key => $label )
        {
            $options                  = $this->params;
            $options['attr']          = $this->attr;
            $options['attr']['id']    = $id . "_$key";
            $options['attr']['name']  = $name . "[$key]";
            $options['attr']['value'] = $key;
            if ( isset( $values[$key] ) && $values[$key] )
            {
                $options['attr']['checked'] = 'checked';
            }
            $widget = new InputCheckboxWidget( $options );
            $contents .= HtmlToolkit::buildTag( 'label', array(), false, $widget->render() . ' ' . $label );
        }

        return $contents;
    }
}
