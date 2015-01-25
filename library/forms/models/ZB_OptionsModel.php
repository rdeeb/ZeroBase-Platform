<?php
require_once(__DIR__.'/ZB_AbstractModel.php');

class ZB_OptionsModel extends ZB_AbstractModel
{
    protected function storeData($name, $value)
    {
        update_option($name, $value);
    }

    protected function retreiveData($name, $default)
    {
        return get_option($name, $default);
    }
}
