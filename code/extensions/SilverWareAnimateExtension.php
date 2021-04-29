<?php

/**
 * An extension of the data extension class which allows extended objects to use animations.
 */
class SilverWareAnimateExtension extends DataExtension
{
    /**
     * Answers a map of animation options for use in a dropdown field.
     *
     * @return array
     */
    public static function get_animation_options()
    {
        $options = Config::inst()->get('SilverWareAnimateExtension', 'animation_options');
        
        return array_combine($options, $options);
    }
    
    /**
     * Answers a map of animation in options for use in a dropdown field.
     *
     * @return array
     */
    public static function get_animation_in_options()
    {
        $options_in = Config::inst()->get('SilverWareAnimateExtension', 'animation_in_options');
        
        $options = array_merge(self::get_animation_options(), $options_in);
        
        return array_combine($options, $options);
    }
    
    /**
     * Answers a map of animation out options for use in a dropdown field.
     *
     * @return array
     */
    public static function get_animation_out_options()
    {
        $options_out = Config::inst()->get('SilverWareAnimateExtension', 'animation_out_options');
        
        $options = array_merge(self::get_animation_options(), $options_out);
        
        return array_combine($options, $options);
    }
}
