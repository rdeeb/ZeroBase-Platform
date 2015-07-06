<?php
require_once(__DIR__.'/ZB_AbstractRenderer.php');

class ZB_TaxonomyRenderer extends ZB_AbstractRender
{
    public function renderRow( $widgetName )
    {
        return ZB_HtmlToolkit::buildTag( 'div', array(
                'class' => "form-field term-$widgetName-wrap"
            ), false,
            $this->renderLabel( $widgetName ).$this->renderWidget( $widgetName )
        );
    }
}
