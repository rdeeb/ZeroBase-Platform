<?php

class InputGalleryWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        \WP_Mock::wpFunction( '__', array(
            'args' => array(
                "Select a gallery",
                "zerobase"
            ),
            'times' => 1,
            'return' => "Select Images"
        ) );
        $widget = new Zerobase\Forms\Widgets\InputGalleryWidget(array(
                'attr' => array(
                    'data-test' => 'assert'
                )
            )
        );
        $this->assertEquals( '<ul class="gallery-preview"></ul><input data-test="assert" value="" type="hidden" /><button type="button" class="button action gallery">Select Images</button>',
            $widget->renderWidget()
        );
    }
}
