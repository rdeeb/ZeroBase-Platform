
class <?php echo $class_name ?> extends \Zerobase\Widgets\BaseWidget
{
    public function getName()
    {
        return '<?php echo $name ?>';
    }

    public function getDescription()
    {
        return '<?php echo $description ?>';
    }

    public function getFields()
    {
        return <?php echo var_export($fields, true) ?>;
    }

    /**
     * getTemplate Returns this widget template path
     *
     * @return void
     * @author Ramy Deeb <me@ramydeeb.com>
     **/
    public function getTemplate()
    {
        return '<?php echo $template ?>';
    }
}

function <?php echo $class_name ?>_loader()
{
    register_widget( '<?php echo $class_name ?>' );
}

add_action( 'widgets_init', '<?php echo $class_name ?>_loader' );
