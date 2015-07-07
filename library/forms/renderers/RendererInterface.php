<?php
namespace Zerobase\Forms\Renderers;

use Zerobase\Forms\Widgets\WidgetInterface;

interface RendererInterface
{
    public function addWidget($name, WidgetInterface $widget);
    public function addWidgets(array $widgets);
    public function render();
    public function renderRow( $widgetName );
    public function renderLabel( $widgetName );
    public function renderWidget( $widgetName );
}
