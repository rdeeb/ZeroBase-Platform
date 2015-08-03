<?php
namespace Zerobase\Toolkit;
/**
 * HtmlToolkit
 * This file helps in building html tags
 *
 * @author  Ramy Deeb <me@ramydeeb.com>
 * @package ZeroBase
 * @license Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License. http://creativecommons.org/licenses/by-nc-nd/3.0/.
 */

class HtmlToolkit
{

    /**
     * Builds an html tag using the passed parameters
     * @param string $tag       the tag to generate i.e.: div
     * @param array $attr       the list of attributes for the tags
     * @param bool $single      is the a single tag? i.e.: <br />
     * @param string $content   the content to render inside the tags
     * @return string
     **/
    public static function buildTag( $tag, array $attr = array(), $single = true, $content = NULL )
    {
        $attrs = ' ';
        foreach ( $attr as $key => $value )
        {
            $attrs .= "$key=\"" . trim( $value ) . "\" ";
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
     * Builds a div tag
     * @param string $content   the content to render inside the tags
     * @param array $attr       the list of attributes for the tags
     * @return string
     **/
    public static function buildDiv( $content, $attr = array() )
    {
        return self::buildTag( 'div', $attr, false, $content );
    }

    /**
     * Builds an input tag
     * @param string $content   the content to render inside the tags
     * @param array $attr       the list of attributes for the tags
     * @return string
     **/
    public static function buildInput( $content, $attr = array() )
    {
        return self::buildTag( 'input', $attr, true, $content );
    }

    /**
     * Builds a label tag
     * @param string $content   the content to render inside the tags
     * @param array $attr       the list of attributes for the tags
     * @return string
     **/
    public static function buildLabel( $content, $attr = array() )
    {
        $attr = array_merge(array(
            'class' => 'uk-form-label'
        ), $attr);
        return self::buildTag( 'label', $attr, false, $content );
    }

    /**
     * slugify
     * Creates a slug from a string
     *
     * @param string $text Text to slugify
     *
     * @return string
     */
    static function slugify( $text )
    {
        // replace non letter or digits by -
        $text = preg_replace( '~[^\\pL\d]+~u', '-', $text );

        // trim
        $text = trim( $text, '-' );

        // transliterate
        $text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );

        // lowercase
        $text = strtolower( $text );

        // remove unwanted characters
        $text = preg_replace( '~[^-\w]+~', '', $text );

        if ( empty( $text ) ) {
            return 'n-a';
        }

        return $text;
    }
}
