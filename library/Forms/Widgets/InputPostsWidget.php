<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

class InputPostsWidget extends BaseInputWidget
{

    protected function supportedParams()
    {
        return array_merge(array(
          'post_type'
        ), parent::supportedParams());
    }

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
        $this->attr['type'] = 'text';
        $this->attr['class'] = 'uk-autocomplete uk-form';
        $this->attr['data-uk-autocomplete'] = '{ source: post_autocomplete_callback }';
        return HtmlToolkit::buildTag( 'input', $this->attr, true );
    }
}
