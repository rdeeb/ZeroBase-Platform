<?php
require_once(__DIR__.'/ZB_AbstractModel.php');

class ZB_MemoryModel extends ZB_AbstractModel
{
    protected function storeData($name, $value)
    {
        return null;
    }

    protected function retreiveData($name, $default)
    {
        return null;
    }
}
