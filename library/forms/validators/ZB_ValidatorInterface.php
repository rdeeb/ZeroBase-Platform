<?php

interface ZB_ValidatorInterface
{
    public function assert();
    public function setValue($value);
    public function getErrors();
}
