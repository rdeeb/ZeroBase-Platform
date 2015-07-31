<?php
namespace Zerobase\Forms\Validators;

interface ValidatorInterface
{
    public function assert();
    public function setValue($value);
    public function getErrors();
}
