<?php
require_once(__DIR__.'/ZB_AbstractRenderer.php');

class ZB_TaxonomyRenderer extends ZB_AbstractRender
{
    public function renderRow( $widgetName )
    {
        return ZB_HtmlToolkit::buildTag( 'tr', array(), false,
            ZB_HtmlToolkit::buildTag( 'td', array( 'colspan' => '2' ), false,
                $this->renderLabel( $widgetName ).$this->renderWidget( $widgetName )
            )
        );
    }
}