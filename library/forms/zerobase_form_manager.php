<?php

class zerobase_form_manager
{

    private $widgets = array();

    private function __construct()
    {
        $this->loadDefaultWidgets();
    }

    /**
     * Returns the instance of this singleton
     * @return zerobase_form_manager
     */
    static function getInstance()
    {
        static $instance = null;
        if (null === $instance)
        {
            $instance = new zerobase_form_manager();
        }
        return $instance;
    }

    /**
     * Loads the default widgets
     */
    private function loadDefaultWidgets()
    {
        $this->addFormWidget('hidden', 'InputHiddenWidget', __DIR__ . '/widgets/InputHiddenWidget.php' );
        $this->addFormWidget('text', 'InputTextWidget', __DIR__ . '/widgets/InputTextWidget.php' );
        $this->addFormWidget('textarea', 'InputTextareaWidget', __DIR__ . '/widgets/InputTextareaWidget.php' );
        $this->addFormWidget('checkbox', 'InputCheckboxWidget', __DIR__ . '/widgets/InputCheckboxWidget.php' );
        $this->addFormWidget('checkbox_list', 'InputCheckboxListWidget', __DIR__ . '/widgets/InputCheckboxListWidget.php' );
        $this->addFormWidget('radio_list', 'InputRadioListWidget', __DIR__ . '/widgets/InputRadioListWidget.php' );
        $this->addFormWidget('select', 'InputSelectWidget', __DIR__ . '/widgets/InputSelectWidget.php' );
        $this->addFormWidget('date', 'InputDateWidget', __DIR__ . '/widgets/InputDateWidget.php' );
        $this->addFormWidget('colorpicker', 'InputColorWidget', __DIR__ . '/widgets/InputColorWidget.php' );
        $this->addFormWidget('image', 'InputImageWidget', __DIR__ . '/widgets/InputImageWidget.php' );
        $this->addFormWidget('file', 'InputFileWidget', __DIR__ . '/widgets/InputFileWidget.php' );
        $this->addFormWidget('gallery', 'InputGalleryWidget', __DIR__ . '/widgets/InputGalleryWidget.php' );
        $this->addFormWidget('google_map', 'InputGoogleMapsWidget', __DIR__ . '/widgets/InputGoogleMapsWidget.php' );
    }

    /**
     * Adds a widget to the manager
     * @param $name string The name of the widget
     * @param $className string The class name of the Widget
     * @param $filePath string The file path to be loaded
     * @throws Exception If the class doesn't implements the WidgetInterface
     */
    public function addFormWidget($name, $className, $filePath)
    {
        if (file_exists($filePath))
        {
            require_once($filePath);
            if (!$this->checkClassImplements($className))
            {
                throw new Exception("The class \"$className\" must implement the WidgetInterface");
            }
            else
            {
                $this->widgets[$name] = $className;
            }
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
     * @throws Exception If the widget doesn't exists
     * @return BaseWidget Returns a widget extending the BaseWidget class
     */
    public function createInstance($name, $options)
    {
        if ($this->widgetExists($name))
        {
            $className = $this->widgets[$name];
            return new $className($options);
        }
        else
        {
            throw new Exception("The widget \"$name\" doesn't exists");
        }
    }

    /**
     * @param $className string The class name to check
     * @return bool True if the class implements the WidgetInterface
     * @throws Exception If the Class doesn't exists or if the class doesn't implements the WidgetInterface
     */
    private function checkClassImplements($className)
    {
        if (!class_exists($className))
        {
            throw new Exception("The class \"$className\" doesn't exists");
        }
        $implements = class_implements($className);
        if (in_array('WidgetInterface', $implements))
        {
            return true;
        }
        return false;
    }
}