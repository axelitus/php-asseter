<?php
/**
 * Part of composer package: axelitus/asseter
 *
 * @package     axelitus\Asseter
 * @version     0.1
 * @author      Axel Pardemann (axelitusdev@gmail.com)
 * @license     MIT License
 * @copyright   2013 - Axel Pardemann
 * @link        http://axelitus.mx/projects/axelitus/asseter
 */

namespace axelitus\Asseter;

/**
 * Class Html
 *
 * @package axelitus\Asseter
 */
final class Html
{
    /**
     * Creates a css tag.
     *
     * Can create an inline or a link tag.
     *
     * @param string       $src    The inline content or the link uri.
     * @param string|array $attr   The tag's attributes.
     * @param bool         $inline Whether to create an inline tag or not.
     *
     * @return string The compiled tag.
     */
    public static function css($src, $attr = [], $inline = false)
    {
        $attr['type'] = (isset($attr['type'])) ? $attr['type'] : 'text/css';
        if ($inline) {
            $css = self::tag('style', $attr, PHP_EOL . $src . PHP_EOL);
        } else {
            $attr['rel'] = (isset($attr['rel'])) ? $attr['rel'] : 'stylesheet';
            $attr['href'] = $src;

            $css = self::tag('link', $attr);
        }

        return $css;
    }

    /**
     * Creates an html tag with the given attributes and content.
     *
     * @param string       $tag         The tag to create.
     * @param string|array $attr        The tag's attributes.
     * @param bool         $content     The tag's content.
     * @param bool         $xhtml_style Whether to use xhtml style or not.
     *
     * @return string The compiled tag
     */
    public static function tag($tag, $attr = [], $content = false, $xhtml_style = false)
    {
        $has_content = (bool)($content !== false and $content !== null);
        $html = '<' . $tag;

        $html .= (!empty($attr)) ? ' ' . (is_array($attr) ? self::arrayToAttr($attr, $xhtml_style) : $attr) : '';
        $html .= $has_content ? '>' : (($xhtml_style) ? ' />' : '>');
        $html .= $has_content ? $content . '</' . $tag . '>' : '';

        return $html;
    }

    /**
     * Converts an array to an html attributes string.
     *
     * The function does not validate any input for attribute names and value validity.
     * By default the function returns a string conforming to HTML5 rules if not otherwise
     * changed by the $xhtml_style parameter.
     *
     * @param array $arr         The attributes array.
     * @param bool  $xhtml_style Whether to use xhtml style or not.
     *
     * @return string The html attributes string.
     */
    public static function arrayToAttr($arr, $xhtml_style = false)
    {
        $attr_str = '';

        foreach ((array)$arr as $property => $value) {
            // Ignore null/false
            if ($value === null or $value === false) {
                continue;
            }

            // If the key is numeric then it must be a boolean attribute
            if (is_numeric($property)) {
                $property = $value;

                if (!$xhtml_style) {
                    $value = null;
                }
            }

            $attr_str .= $property . (($value === null) ? ' ' : '="' . $value . '" ');
        }

        // We strip off the last space for return
        return trim($attr_str);
    }

    /**
     * Creates a script tag.
     *
     * Can create an inline or a script src tag.
     *
     * @param string       $src    The inline content or the script src uri.
     * @param string|array $attr   The tag's attributes.
     * @param bool         $inline Whether to create an inline tag or not.
     *
     * @return string
     */
    public static function script($src, $attr = [], $inline = false)
    {
        $attr['type'] = (isset($attr['type'])) ? $attr['type'] : 'text/javascript';
        if ($inline) {
            $script = self::tag('script', $attr, PHP_EOL . $src . PHP_EOL);
        } else {
            $attr['src'] = $src;

            $script = self::tag('script', $attr, '');
        }

        return $script;
    }

    /**
     * Creates an img tag.
     *
     * Can create an img tag with inline content or src uri. The mime-type should be given through $attr['mime-type'].
     * Also the inline content charset can be given with $attr['charset'].
     *
     * @param string       $src         The inline content (data uri @see http://en.wikipedia.org/wiki/Data_URI_scheme) or the link uri.
     * @param string|array $attr        The tag's attributes.
     * @param bool         $inline      Whether to create an inline tag or not.
     * @param bool         $xhtml_style Whether to use xhtml style or not.
     *
     * @return string
     */
    public static function img($src, $attr = [], $inline = false, $xhtml_style = false)
    {
        if ($inline) {
            $attr['mime-type'] = (isset($attr['mime-type'])) ? $attr['mime-type'] : 'image';
            $attr['charset'] = (isset($attr['charset'])) ? ';' . $attr['charset'] : '';
            $attr['src'] = 'data:' . $attr['mime'] . $attr['charset'] . ';base64,' . $src;

            // unset additional attributes
            unset($attr['mime-type']);
            unset($attr['charset']);
        } else {
            $attr['src'] = $src;
            $attr['alt'] = (isset($attr['alt'])) ? $attr['alt'] : pathinfo($src, PATHINFO_FILENAME);
        }

        return self::tag('img', $attr, $xhtml_style);
    }
}
