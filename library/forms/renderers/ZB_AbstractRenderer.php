<?php
include_once( __DIR__.'/ZB_RendererInterface.php' );

abstract class ZB_AbstractRender implements ZB_RendererInterface
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
        return ZB_HtmlToolkit::buildDiv(
            $this->renderLabel( $widgetName ).$this->renderWidget( $widgetName ),
            array(
                'class' => 'form-group'
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
            throw new Exception("The widget \"$widgetName\" doesn't exists.");
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
            throw new Exception("The widget \"$widgetName\" doesn't exists.");
        }
    }

    public function widgetExists( $widgetName )
    {
        return isset($this->widgets[$widgetName]);
    }
}