<?php

class InputColorWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        $widget = new Zerobase\Forms\Widgets\InputColorWidget(array(
                'attr' => array(
                    'data-test' => 'assert'
                )
            )
        );
        $this->assertEquals( '<input data-test="assert" value="" type="text" class="colorselector" />', $widget->renderWidget() );
    }
}