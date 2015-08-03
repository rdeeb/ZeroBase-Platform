<?php

class InputGoogleMapsWidgetTest extends PHPUnit_Framework_TestCase
{
    public function testWidget()
    {
        $widget = new Zerobase\Forms\Widgets\InputGoogleMapsWidget(array(
                'attr' => array(
                    'data-test' => 'assert'
                )
            )
        );
        $this->assertEquals( '<div class="map-selector"><div class="map-canvas"></div><input data-test="assert" value="" type="hidden" class="gmap-latlong" /></div>',
            $widget->renderWidget()
        );
    }
}