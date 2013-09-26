<?php
/**
 * Plugin Name: ZeroBase Platform
 * Plugin URI: https://github.com/rdeeb/ZeroBase-Platform
 * Description: This is the base of the ZeroBase Wordpress Framework.
 * Version: 0.2
 * Author: Ramy Deeb
 * Author URI: http://www.ramydeeb.com
 * License: Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 *
 * @author Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 */

//Load the toolkit
require_once( __dir__.'/toolkit/zerobase_html_toolkit.php' );
//Load the Form Builder
require_once( __dir__.'/forms/zerobase_form_builder.php' );
require_once( __dir__.'/forms/zerobase_tax_form_builder.php' );
require_once( __dir__.'/forms/zerobase_widget_form_builder.php' );
//Load the Metabox Builder
require_once( __dir__.'/metaboxes/zerobase_metabox.php' );
//Load the post type interface and the widgets base class
require_once( __dir__.'/post-types/zerobase_post_type_interface.php' );
require_once( __dir__.'/widgets/zerobase_base_widget.php' );

function zerobase_framework_init()
{
    //Register the color picker script & styles
    wp_register_script(
        'zerobase_js_colorpicker',
        get_template_directory_uri().'/lib/framework/forms/js/colorpicker.min.js',
        array (
            'jquery'
        ),
        null,
        true
    );
    wp_register_style(
        'zerobase_css_colorpicker',
        get_template_directory_uri().'/lib/framework/forms/css/colorpicker.css'
    );
    wp_register_script(
        'zerobase_js_forms',
        get_template_directory_uri().'/lib/framework/forms/js/forms.min.js',
        array (
            'jquery',
            'jquery-ui-core',
            'jquery-ui-widget',
            'jquery-ui-datepicker',
            'media-upload',
            'thickbox'
        ),
        null,
        true
    );
    wp_localize_script( 'zerobase_js_forms', 'forms_trans', array(
        'gallery_title' => __( 'Select the images for the gallery', 'zerobase' ),
        'gallery_submit' => __( 'Choose gallery', 'zerobase' ),
        'image_title' => __( 'Select an image', 'zerobase' ),
        'image_submit' => __( 'Choose image', 'zerobase' ),
        'file_title' => __( 'Select a file', 'zerobase' ),
        'file_submit' => __( 'Choose file', 'zerobase' ),
    ) );
    wp_register_style(
        'zerobase_css_forms',
        get_template_directory_uri().'/lib/framework/forms/css/forms.css',
        array(
            'thickbox'
        )
    );
}