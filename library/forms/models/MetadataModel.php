<?php
namespace Zerobase\Forms\Models;

class MetadataModel extends AbstractModel
{

    protected function getPostId()
    {
        global $post;
        if (!empty($post) && $post->ID)
        {
            return $post->ID;
        }
        else if (isset($_REQUEST['post']))
        {
            return $_REQUEST['post'];
        }
        else if (isset( $_POST['post_ID'] ))
        {
            return $_POST['post_ID'];
        }
    }

    protected function storeData($name, $value)
    {
        update_post_meta($this->getPostId(), $name, $value);
    }

    protected function retreiveData($name, $default)
    {
        $value = get_post_meta($this->getPostId(), $name, true);
        return empty($value) ? $default : $value;
    }
}
