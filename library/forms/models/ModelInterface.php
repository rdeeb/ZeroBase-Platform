<?php
namespace Zerobase\Forms\Models;

interface ModelInterface
{
    public function setModel(array $model);
    public function setValue($name, $value);
    public function getValue($name);
    public function getValues();
    public function save();
}
