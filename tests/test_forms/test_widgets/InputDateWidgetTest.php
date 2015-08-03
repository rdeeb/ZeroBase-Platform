<?php

class InputDateWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        \WP_Mock::wpFunction( 'get_option', array(
            'args' => 'date_format',
            'times' => 1,
            'return' => 'd/m/Y'
        ) );
        $widget = new Zerobase\Forms\Widgets\InputDateWidget(array(
                'attr' => array(
                    'data-test' => 'assert'
                )
            )
        );
        $this->assertEquals( '<input data-test="assert" value="" type="text" data-dateFormat="d/m/Y" class="datepicker" />', $widget->renderWidget() );
    }
}