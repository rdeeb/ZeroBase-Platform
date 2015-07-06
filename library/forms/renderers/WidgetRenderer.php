<?php
namespace Zerobase\Forms\Renderers;

use Zerobase\Toolkit\HtmlToolkit;

class WidgetRenderer extends AbstractRender
{
    public function renderRow( $widgetName )
    {
        return HtmlToolkit::buildTag( 'p', array(), false, $this->renderLabel( $widgetName ) . "\n" . $this->renderWidget( $widgetName ) );
    }
}
