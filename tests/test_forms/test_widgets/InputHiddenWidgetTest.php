<?php

class InputHiddenWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        $widget = new Zerobase\Forms\Widgets\InputHiddenWidget(array(
                'attr' => array(
                    'data-test' => 'assert'
                )
            )
        );
        $this->assertEquals( '<input data-test="assert" value="" type="hidden" />', $widget->renderWidget() );
    }
}
