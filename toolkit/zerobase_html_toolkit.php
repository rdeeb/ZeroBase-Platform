<?php
/**
 * zerobase_html_toolkit
 * This file helps in building html tags
 *
 * @author Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 */
 
class zerobase_html_toolkit {
    
    /**
     * buildTag
     * Builds an html tag using the passed parameters
     *
     * @return string
     * @param $tag string the tag to generate i.e.: div
     * @param $attr array the list of attributes for the tags
     * @param $single bool is the a single tag? i.e.: <br />
     * @param $content string the content to render inside the tags
     * @author Ramy Deeb
     **/
    public static function buildTag( $tag, array $attr = array(), $single = true, $content = null )
    {
        $attrs = ' ';
        foreach ( $attr as $key => $value )
        {
            $attrs .= "$key=\"$value\" ";
        }
        $attrs = rtrim( $attrs );
        if ( $single ) 
        {
            return "<$tag$attrs />";
        }
        else
        {
            return "<$tag$attrs>$content</$tag>";
        }
    }
    
    /**
     * buildDiv
     * Builds a div tag
     *
     * @return string
     * @param $content string the content to render inside the tags
     * @param $attr array the list of attributes for the tags
     * @author Ramy Deeb
     **/
    public static function buildDiv($content, $attr = array())
    {
        return self::buildTag('div', $attr, false, $content);
    }
    
    /**
     * buildInput
     * Builds an input tag
     *
     * @return string
     * @param $content string the content to render inside the tags
     * @param $attr array the list of attributes for the tags
     * @author Ramy Deeb
     **/
    public static function buildInput($content, $attr = array())
    {
        return self::buildTag('input', $attr, true, $content);
    }
}