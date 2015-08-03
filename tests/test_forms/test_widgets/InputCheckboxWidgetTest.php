<?php

class InputCheckboxWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        $widget = new Zerobase\Forms\Widgets\InputCheckboxWidget(array(
                'attr' => array(
                    'data-test' => 'assert'
                )
            )
        );
        $this->assertEquals( '<input data-test="assert" value="1" type="checkbox" />', $widget->renderWidget() );
    }
}