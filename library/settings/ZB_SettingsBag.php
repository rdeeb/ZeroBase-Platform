<?php

class ZB_SettingsBag implements Iterator, ArrayAccess
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
        $name_re = '/^[a-z0-9_-]{3,99}$/';
        if ($name && preg_match($name_re, $name))
        {
            $fm = ZB_WidgetFactory::getInstance();
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
            throw new Exception("The setting name must be a valid name between 3 and 99 characters in length");
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

    /**
     * Returns the form builders for each sub settings page
     * @param $form_name string
     * @return array
     */
    public function getPages($form_name)
    {
        $pages = array();
        foreach ($this->settingsBag as $widget_name => $options)
        {
            if (!isset($pages[$options['section']]))
            {
                $pages[$options['section']] = ZB_FormFactory::createForm($form_name.'-'.$options['section'], 'options');
            }
            $pages[$options['section']]->addWidget($widget_name, $options['widget'], $options['options']['widget_options'], get_option($widget_name, isset($options['options']['default']) ? $options['options']['default'] : null));
        }
        return $pages;
    }

    public function isEmpty()
    {
        return empty($this->settingsBag);
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
        return $this->bagIndexes[$this->position];
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
        if (is_null($offset)) {
            $this->settingsBag[] = $value;
        } else {
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
