<?php

class InputCheckboxListWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        $widget = new Zerobase\Forms\Widgets\InputCheckboxListWidget(array(
                'attr' => array(
                    'data-test' => 'assert'
                ),
                'choices' => array(
                    1 => 'Select One',
                    2 => 'Select Two'
                )
            )
        );
        $this->assertEquals( '<label><input data-test="assert" id="_1" name="[1]" value="1" type="checkbox" /> Select One</label><label><input data-test="assert" id="_2" name="[2]" value="1" type="checkbox" /> Select Two</label>',
            $widget->renderWidget() );
    }
}