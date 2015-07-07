<?php
namespace Zerobase\Forms\Renderers;

use Zerobase\Toolkit\HtmlToolkit;

class OptionsRenderer extends AbstractRenderer
{
    public function renderRow( $widgetName )
    {
        return HtmlToolkit::buildTag( 'tr', array(), false,
          HtmlToolkit::buildTag( 'th', array( 'scope' => 'row' ), false, $this->renderLabel( $widgetName ) )
          .
          HtmlToolkit::buildTag( 'td', array(), false, $this->renderWidget( $widgetName ) )
        );
    }
}
