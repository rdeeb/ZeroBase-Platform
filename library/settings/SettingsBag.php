<?php
namespace Zerobase\Settings;

use Zerobase\Forms\FormFactory;
use Zerobase\Forms\Widgets\WidgetFactory;
use Zerobase\Toolkit\Iterator;

class SettingsBag extends Iterator
{
    public function addSetting($name, $widget, array $options = array(), $section = 'General')
    {
        $name_re = '/^[a-z0-9_-]{3,99}$/';
        if ($name && preg_match($name_re, $name))
        {
            $fm = WidgetFactory::getInstance();
            if (!$fm->widgetExists($widget))
            {
                throw new \Exception("The widget \"$widget\" is not a supported widget type");
            }
            if (!$this->offsetExists($name))
            {
                $this->indexes[] = $name;
            }
            $this->offsetSet($name, array(
                'widget' => $widget,
                'options' => $options,
                'section' => $section
            ));
            return $this;
        }
        else
        {
            throw new \Exception("The setting name must be a valid name between 3 and 99 characters in length");
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
            throw new \Exception("The setting \"$name\" is not set");
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
        foreach ($this->data as $widget_name => $options)
        {
            if (!isset($pages[$options['section']]))
            {
                $pages[$options['section']] = FormFactory::createForm($form_name.'-'.$options['section'], 'options', 'option');
            }
            $pages[$options['section']]->addWidget($widget_name, $options['widget'], $options['options']['widget_options'], get_option($widget_name, isset($options['options']['default']) ? $options['options']['default'] : null));
        }
        return $pages;
    }
}
