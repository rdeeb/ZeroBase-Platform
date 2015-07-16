<?php
namespace Zerobase\Forms\Renderers;

use Zerobase\Toolkit\HtmlToolkit;

class TaxonomyRenderer extends AbstractRenderer
{
    public function renderRow( $widgetName )
    {
        return HtmlToolkit::buildTag( 'div', array(
                'class' => "form-field term-$widgetName-wrap"
            ), false,
            $this->renderLabel( $widgetName ).$this->renderWidget( $widgetName )
        );
    }
}
