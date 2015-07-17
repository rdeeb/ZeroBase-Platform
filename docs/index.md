# Zerobase Platform Docs

Zerobase will allow you to develop a plugin or theme with ease, by writing rules in a YAML file,
you will be able to create different elements for your Wordpress site.

## Anatomy of a Zerobase Plugin

A tipical Zerobase powered plugin will have the following structure:

```
wp_contents/plugins
    + my_zb_plugin/
    |-- my_zb_plugin.php
    |-- my_zb_plugin.post_type.yml
    |-- my_zb_plugin.taxonomy.yml
    |-- my_zb_plugin.metabox.yml
```

### my_zb_plugin.php

This file is your standard Wordpress plugin file, this is where you are going to write the only "required"
pieces of code that are used to activate your Zerobase Powered Plugin. This file will look something
like this:

``` php
/*
Plugin Name: My Zerobase Plugin
Description: A plugin to test the Zerobase Platform
Version: 1.0
Author: Ramy Deeb
Author URI: http://www.ramydeeb.com/
License: MIT License.
*/
function my_zb_module( ZerobasePlatform $platform )
{
    $platform->addModule(array(
      'name' => 'My ZB Module',
      'path' => plugin_dir_path( __FILE__ )
    ));
}

add_action( 'zerobase_load_modules', 'my_zb_module' );
```

As you can see we will have the default Wordpress plugin definition, after that you will see the Zerobase
Module definition inside the callback function ``` php function my_zb_module( ZerobasePlatform $platform ) ```
This function will receive as parameter the ZerobasePlatform current instance, during all the execution of
your Wordpress request there will be only one instance of the Zerobase Platform.

Inside this function you will define your module, after you add it Zerobase will do it's magic and load the
yaml files and convert them into the required PHP code that will create your Post Types, Taxonomies, Widgets
even Settings pages.

Lastly you will have to register your callback using the ```add_action( 'zerobase_load_modules', 'my_zb_module' );```

## YAML Loaders

The platform offers a set of configuration loaders that will transform YAML files into usable Wordpress
components, as of right now you can create the following components from YAML files:

* [Post Types](Post-Types.md)
* Metaboxes
* Taxonomies
* Widgets
* Javascripts & CSS
