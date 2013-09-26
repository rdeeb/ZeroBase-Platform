# Widget Class

The Base widget class allows you to create a new widget with few implementations as possible.
The class handles the widget form by relying on the form builder class.

It automatically saves the data and makes it available for you in the view.

## Usage

Create a new class and extend `zerobase_base_widget`. After you do this the only thing left is
to update the required functions for the widget to work. View the next example:

## Example

```php
<?php

class ZeroExampleWidget extends zerobase_base_widget
{
    /**
     * getName Returns this widget name
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function getName()
    {
        return 'ZeroBase Example Widget';
    }

    /**
     * getDescription Returns this widget description
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function getDescription()
    {
        return 'This is just a test widget';
    }

    /**
     * getFields Returns this widget fields
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function getFields()
    {
        return array(
            'title' => array(                   //The name of the field
                'type' => 'text',               //The type of widget to display
                'default' => 'Test Widget',     //The default value
                'label' => 'Title'              //The widget label (Optional)
            ),
            'name' => array(
                'type' => 'text',
                'default' => 'John Doe',
                'lable' => 'Your Name'
            ),
        );
    }

    /**
     * getTemplate Returns this widget template path
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    private function getTemplate()
    {
        return 'path/to/the/template.php';
    }
}
```

In here you can see that we have defined four functions, each of them are required in order
that the widget class can function properly