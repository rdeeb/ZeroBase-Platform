<?php
namespace Zerobase\Forms\Renderers;

use Zerobase\Toolkit\HtmlToolkit;

class OptionsRenderer extends AbstractRenderer
{
    public function renderRow( $widgetName )
    {
        return HtmlToolkit::buildTag( 'tr', array(), FALSE,
            HtmlToolkit::buildTag( 'th', array( 'scope' => 'row' ), FALSE, $this->renderLabel( $widgetName ) )
            .
            HtmlToolkit::buildTag( 'td', array(), FALSE, $this->renderWidget( $widgetName ) )
        );
    }
}
