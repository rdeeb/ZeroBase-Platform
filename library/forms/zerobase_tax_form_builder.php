<?php
require_once( __DIR__ . '/zerobase_form_builder.php' );

/**
 * zerobase_form_builder
 * A class that builds options forms for Wordpress
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
class zerobase_tax_form_builder extends zerobase_form_builder
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
        return zerobase_html_toolkit::buildTag( 'div', array(
                'class' => 'form-field'
            ), false, $this->renderLabel( $name ) . "\n" . $this->renderWidget( $name ) );
    }

    public function renderTrRow( $name )
    {
        return zerobase_html_toolkit::buildTag( 'tr', array(), false, zerobase_html_toolkit::buildTag( 'td', array( 'colspan' => '2' ), false, $this->renderRow( $name ) ) );
    }
} // END class FormBuilder