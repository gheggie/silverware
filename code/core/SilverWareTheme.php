<?php

/**
 * An extension of the object class for the core SilverWare theme object.
 */
class SilverWareTheme extends Object
{
    /**
     * @config
     * @var array
     */
    private static $themed_css = array(
        'normalize',
        'grid',
        'typography',
        'form',
        'pagination',
        'silverware',
        'faloading'
    );
    
    /**
     * @config
     * @var array
     */
    private static $extra_css = array();
    
    /**
     * @config
     * @var array
     */
    private static $extra_typography_css = array();
    
    /**
     * Answers an array of extra CSS files.
     *
     * @return array
     */
    public static function get_extra_css()
    {
        return self::config()->get('extra_css');
    }
    
    /**
     * Answers an array of extra typography CSS files.
     *
     * @return array
     */
    public static function get_extra_typography_css()
    {
        return self::config()->get('extra_typography_css');
    }
    
    /**
     * Loads the CSS required for the theme.
     */
    public static function load_css()
    {
        foreach (self::config()->get('themed_css') as $name) {
            Requirements::themedCSS($name);
        }
        
        foreach (self::config()->get('extra_css') as $file) {
            Requirements::css($file);
        }
        
        foreach (self::config()->get('extra_typography_css') as $file) {
            Requirements::css($file);
        }
    }
}
