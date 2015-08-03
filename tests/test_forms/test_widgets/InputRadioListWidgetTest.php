<?php

class InputRadioListWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        $widget = new Zerobase\Forms\Widgets\InputRadioListWidget( array(
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
        $this->assertEquals( '<p><label class="inline-block"><input data-test="assert" type="radio" value="1" checked="checked" /> Select One</label></p><p><label class="inline-block"><input data-test="assert" type="radio" value="2" /> Select Two</label></p>',
            $widget->renderWidget() );
    }
}