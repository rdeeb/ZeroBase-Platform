<?php
namespace Zerobase\Forms\Widgets;

use Zerobase\Toolkit\Singleton;

class WidgetFactory extends Singleton
{

    private $widgets = array();

    protected function __construct()
    {
        $this->loadDefaultWidgets();
    }

    private function loadDefaultWidgets()
    {
        $this->addFormWidget('hidden', 'InputHiddenWidget' );
        $this->addFormWidget('text', 'InputTextWidget' );
        $this->addFormWidget('textarea', 'InputTextareaWidget' );
        $this->addFormWidget('checkbox', 'InputCheckboxWidget' );
        $this->addFormWidget('checkbox_list', 'InputCheckboxListWidget' );
        $this->addFormWidget('radio_list', 'InputRadioListWidget' );
        $this->addFormWidget('select', 'InputSelectWidget' );
        $this->addFormWidget('date', 'InputDateWidget' );
        $this->addFormWidget('colorpicker', 'InputColorWidget' );
        $this->addFormWidget('image', 'InputImageWidget' );
        $this->addFormWidget('file', 'InputFileWidget' );
        $this->addFormWidget('gallery', 'InputGalleryWidget' );
        $this->addFormWidget('google_map', 'InputGoogleMapsWidget' );
    }

    /**
     * Adds a widget to the Factory
     * @param $name string The name of the widget
     * @param $className string The class name of the Widget
     * @throws \Exception If the class doesn't implements the WidgetInterface
     */
    public function addFormWidget($name, $className)
    {
        if (!$this->checkClassImplements($className))
        {
            throw new \Exception("The class \"$className\" must implement the WidgetInterface");
        }
        else
        {
            $this->widgets[$name] = $className;
        }
    }

    /**
     * Checks if a widget type exists
     * @param $name string The name of the widget to check
     * @return bool True if the widget type exists
     */
    public function widgetExists($name)
    {
        return array_key_exists($name, $this->widgets);
    }

    /**
     * Creates a new instance of a widget
     * @param $name string The name of the widget
     * @param $options array The options for the widget
     * @throws \Exception If the widget doesn't exists
     * @return BaseWidget Returns a widget extending the BaseInputWidget class
     */
    public function createWidget($name, $options)
    {
        if ($this->widgetExists($name))
        {
            $className = 'Zerobase\Forms\Widgets\\' . $this->widgets[$name];
            return new $className($options);
        }
        else
        {
            throw new \Exception("The widget \"$name\" doesn't exists");
        }
    }

    /**
     * @param $className string The class name to check
     * @return bool True if the class implements the WidgetInterface
     * @throws \Exception If the Class doesn't exists or if the class doesn't implements the WidgetInterface
     */
    private function checkClassImplements($className)
    {
        $class = 'Zerobase\Forms\Widgets\\' . $className;
        try
        {
            $obj = new $class();
            unset($obj);
        }
        catch(\Exception $e)
        {
            throw new \Exception("The class \"$className\" doesn't exists");
        }
        $implements = class_implements($class);
        if (in_array('Zerobase\Forms\Widgets\WidgetInterface', $implements))
        {
            return true;
        }
        return false;
    }
}
