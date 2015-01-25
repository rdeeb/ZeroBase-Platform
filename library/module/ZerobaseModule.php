<?php

include_once( 'ZerobaseModuleInterface.php' );

abstract class ZerobaseModule implements ZeroBaseModuleInterface
{
    public function __construct()
    {

    }

    public function getName()
    {
        throw new Exception("You need to define a name for the Module");
    }

    public function getDescription()
    {
        throw new Exception("You need to give a description for the Module");
    }

    public function getLoadPath()
    {

    }
}
