<?php

class zerobase_setting_bag implements Iterator, ArrayAccess
{
    protected $settingsBag = array();
    protected $bagIndexes = array();
    private $position = 0;

    public function __construct()
    {
        $this->position = 0;
    }

    public function addSetting($name, $widget, array $options = array(), $section = 'General')
    {
        $name_re = '/^[a-z0-9_-]{3,25}$/';
        if ($name && preg_match($name_re, $name))
        {
            $fm = zerobase_form_manager::getInstance();
            if (!$fm->widgetExists($widget))
            {
                throw new Exception("The widget \"$widget\" is not a supported widget type");
            }
            if (!$this->offsetExists($name))
            {
                $this->bagIndexes[] = $name;
            }
            $this->offsetSet($name, array(
                'widget' => $widget,
                'options' => $options,
                'section' => $section
            ));
        }
        else
        {
            throw new Exception("The setting name must be a valid name between 3 and 25 characters in length");
        }
    }

    public function removeSetting($name)
    {
        if ($this->offsetExists($name))
        {
            $this->offsetUnset($name);
        }
        else
        {
            throw new Exception("The setting \"$name\" is not set");
        }
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->settingsBag[$this->bagIndexes[$this->position]];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->settingsBag[$this->bagIndexes[$this->position]]);
    }

    public function offsetExists($offset)
    {
        return isset($this->settingsBag[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->settingsBag[] = $value;
        }
        else
        {
            $this->settingsBag[$offset] = $value;
        }
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->settingsBag[$offset] : null;
    }

    public function offsetUnset($offset)
    {
        unset($this->settingsBag[$offset]);
    }
}
