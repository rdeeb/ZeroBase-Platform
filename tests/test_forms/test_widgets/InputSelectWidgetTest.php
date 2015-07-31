<?php

class InputSelectWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        $widget = new Zerobase\Forms\Widgets\InputSelectWidget( array(
                'attr' => array(
                    'data-test' => 'assert'
                ),
                'choices' => array(
                    1 => 'Select One',
                    2 => 'Select Two'
                )
            )
        );
        $widget->setValue( 1 );
        $this->assertEquals( '<select data-test="assert"><option value="1" selected="selected">Select One</option><option value="2">Select Two</option></select>',
            $widget->renderWidget() );
    }
}