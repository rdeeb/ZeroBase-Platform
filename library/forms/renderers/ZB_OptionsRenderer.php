<?php
require_once(__DIR__.'/ZB_AbstractRenderer.php');

class ZB_OptionsRenderer extends ZB_AbstractRender
{
    public function renderRow( $widgetName )
    {
        return ZB_HtmlToolkit::buildTag( 'tr', array(), false, ZB_HtmlToolkit::buildTag( 'th', array( 'scope' => 'row' ), false, $this->renderLabel( $widgetName ) ).ZB_HtmlToolkit::buildTag( 'td', array(), false, $this->renderWidget( $widgetName ) ) );
    }
}
