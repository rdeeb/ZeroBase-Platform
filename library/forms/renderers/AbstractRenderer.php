<?php
namespace Zerobase\Forms\Renderers;

use Zerobase\Forms\Widgets\WidgetInterface;
use Zerobase\Toolkit\HtmlToolkit;

abstract class AbstractRenderer implements RendererInterface
{
    protected $widgets = array();

    public function __construct(array $widgets = array())
    {
        $this->addWidgets($widgets);
    }

    public function addWidget($name, WidgetInterface $widget)
    {
        $this->widgets[$name] = $widget;
    }

    public function addWidgets(array $widgets)
    {
        if (!empty($widgets))
        {
            foreach ($widgets as $name => $widget) {
                $this->addWidget($name, $widget);
            }
        }
    }

    public function render()
    {
        if (empty($this->widgets))
        {
            return '';
        }
        $returnString = '';
        foreach($this->widgets as $name => $widget)
        {
            $returnString .= $this->renderRow( $name )."\n";
        }
        return $returnString;
    }

    public function renderRow($widgetName)
    {
        return HtmlToolkit::buildDiv(
            $this->renderLabel( $widgetName )
            .
            HtmlToolkit::buildDiv(
                $this->renderWidget( $widgetName ),
                array(
                    'class' => 'uk-form-controls'
                )
            ),
            array(
                'class' => 'uk-form-row'
            )
        );
    }

    public function renderLabel( $widgetName )
    {
        if ($this->widgetExists($widgetName))
        {
            $widget = $this->widgets[$widgetName];
            return $widget->renderLabel();
        }
        else
        {
            throw new \Exception("The widget \"$widgetName\" doesn't exists.");
        }
    }

    public function renderWidget( $widgetName )
    {
        if ($this->widgetExists($widgetName))
        {
            $widget = $this->widgets[$widgetName];
            return $widget->renderWidget();
        }
        else
        {
            throw new \Exception("The widget \"$widgetName\" doesn't exists.");
        }
    }

    public function widgetExists( $widgetName )
    {
        return isset($this->widgets[$widgetName]);
    }
}
