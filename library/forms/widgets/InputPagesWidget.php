<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\HtmlToolkit;

class InputPagesWidget extends BaseInputWidget
{
    protected function supportedParams()
    {
        return array_merge(array(
          'add_blank'
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
        return 'pages';
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
        if ( !isset( $this->params['add_blank'] ) || $this->params['add_blank'] !== false ) {
            $content .= HtmlToolkit::buildTag( 'option', array(), false, '' );;
        }
        foreach ( get_pages() as $key => $page )
        {
            $page_title = $page->post_title;
            if ( $page->post_parent )
            {
                $page_title = " - $page_title";
            }
            if ( $this->getValue() == $page->ID )
            {
                $content .= HtmlToolkit::buildTag( 'option', array(
                  'value'    => $page->ID,
                  'selected' => 'selected'
                ), false, $page_title );
            }
            else
            {
                $content .= HtmlToolkit::buildTag( 'option', array(
                  'value' => $page->ID
                ), false, $page_title );
            }
        }

        return HtmlToolkit::buildTag( 'select', $this->attr, false, $content );
    }
}
