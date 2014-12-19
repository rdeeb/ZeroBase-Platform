<?php
require_once(__DIR__.'/ZB_AbstractRenderer.php');

class ZB_WidgetRenderer extends ZB_AbstractRender
{
    public function renderRow( $widgetName )
    {
        return ZB_HtmlToolkit::buildTag( 'p', array(), false, $this->renderLabel( $widgetName ) . "\n" . $this->renderWidget( $widgetName ) );
    }
}