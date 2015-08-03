<?php

class InputImageWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        \WP_Mock::wpFunction( '__', array(
            'args' => array(
                "Select File",
                "zerobase"
            ),
            'times' => 1,
            'return' => "Select File"
        ) );
        $widget = new Zerobase\Forms\Widgets\InputImageWidget( array(
                'attr' => array(
                    'data-test' => 'assert'
                )
            )
        );
        $this->assertEquals( '<input data-test="assert" value="" type="hidden" /><button type="button" class="button action image_selector">Select File</button>',
            $widget->renderWidget() );
    }
}
