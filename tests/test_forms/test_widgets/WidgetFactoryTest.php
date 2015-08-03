<?php

class WidgetFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $widget_factory = \Zerobase\Forms\Widgets\WidgetFactory::getInstance();
        $this->assertInstanceOf( '\Zerobase\Forms\Widgets\WidgetFactory', $widget_factory );
    }

    public function testCreateTextWidget()
    {
        $widget_factory = \Zerobase\Forms\Widgets\WidgetFactory::getInstance();
        $widget = $widget_factory->createWidget( 'text', array(
            'attr' => array(
                'data-test' => 'assert'
            )
        ) );
        $this->assertInstanceOf( '\Zerobase\Forms\Widgets\InputTextWidget', $widget );
    }
}
