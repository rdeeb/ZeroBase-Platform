<?php

class InputTextWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        \WP_Mock::wpFunction( 'sanitize_text_field', array(
            'args' => '',
            'times' => 1,
            'return' => ''
        ) );
        $widget = new Zerobase\Forms\Widgets\InputTextWidget(array(
                'attr' => array(
                    'data-test' => 'assert'
                )
            )
        );
        $this->assertEquals( '<input data-test="assert" value="" type="text" />', $widget->renderWidget() );
    }
}
