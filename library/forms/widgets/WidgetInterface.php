<?php
/**
 * WidgetInterface
 * Defines the base of the options widgets
 *
 * @package ZeroBase
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
interface WidgetInterface
{
    public function getType();

    public function renderWidget();

    public function renderLabel();

    public function getValue();

    public function setValue( $v );

}
