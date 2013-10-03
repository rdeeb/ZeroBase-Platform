<?php
/**
 * zerobase_post_type_interface Interface for designing
 *
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @package Zerobase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
interface zerobase_post_type_interface
{
    public function configure();

    public function getName();

    public function getDescription();

    public function getOptions();
} // END interface zerobase_post_type_interface