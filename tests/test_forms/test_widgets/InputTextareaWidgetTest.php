<?php

class InputTextareaWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        \WP_Mock::wpFunction( 'sanitize_text_field', array(
            'args' => 'Test content',
            'times' => 1,
            'return' => 'Test content'
        ) );
        $widget = new Zerobase\Forms\Widgets\InputTextareaWidget( array(
                'attr' => array(
                 'data-test' => 'assert'
                )
            )
        );
        $widget->setValue( 'Test content' );
        $this->assertEquals( '<textarea data-test="assert">Test content</textarea>', $widget->renderWidget() );
    }
}
