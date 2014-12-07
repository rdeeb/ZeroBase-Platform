<?php
require_once( __DIR__ . '/ZB_Form.php' );

/**
 * ZB_WidgetForm
 * A class that builds options forms for Wordpress Widgets
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class ZB_WidgetForm extends ZB_Form
{
    public function render()
    {
        $str = '';
        foreach ( $this->widgets as $name => $widget )
        {
            $str .= $this->renderRow( $name ) . "\n";
        }

        return $str;
    }

    public function renderRow( $name )
    {
        return ZB_HtmlToolkit::buildTag( 'p', array(), false, $this->renderLabel( $name ) . "\n" . $this->renderWidget( $name ) );
    }
} // END class ZB_WidgetForm