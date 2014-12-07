<?php
/**
 * ZB_PostTypeInterface Interface for designing
 *
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @package Zerobase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 **/
interface ZB_PostTypeInterface
{
    public function configure();

    public function getName();

    public function getDescription();

    public function getOptions();
} // END interface ZB_PostTypeInterface