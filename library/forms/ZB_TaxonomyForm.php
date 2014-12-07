<?php
require_once( __DIR__ . '/ZB_Form.php' );

/**
 * ZB_Form
 * A class that builds options forms for Wordpress
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class ZB_TaxonomyForm extends ZB_Form
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

    public function renderTr()
    {
        $str = '';
        foreach ( $this->widgets as $name => $widget )
        {
            $str .= $this->renderTrRow( $name ) . "\n";
        }

        return $str;
    }

    public function renderRow( $name )
    {
        return ZB_HtmlToolkit::buildTag( 'div', array(
                'class' => 'form-field'
            ), false, $this->renderLabel( $name ) . "\n" . $this->renderWidget( $name ) );
    }

    public function renderTrRow( $name )
    {
        return ZB_HtmlToolkit::buildTag( 'tr', array(), false, ZB_HtmlToolkit::buildTag( 'td', array( 'colspan' => '2' ), false, $this->renderRow( $name ) ) );
    }
} // END class FormBuilder