<?php

/**
 * Static class providing utility functions for use with SilverWare.
 */
class SilverWareTools
{
    /**
     * Converts the given nicely formatted column size to a CSS class.
     *
     * @param string $size
     * @return string
     */
    public static function nice_column_to_css($size)
    {
        if ($size != 'none') {
            
            return $size . " " . self::column_noun($size);
            
        }
    }
    
    /**
     * Converts the given nicely formatted offset size to a CSS class.
     *
     * @param string $size
     * @return string
     */
    public static function nice_offset_to_css($size)
    {
        if ($size != 'none') {
            
            return "offset-by-" . $size;
            
        }
    }
    
    /**
     * Converts the given nicely formatted column size to an integer.
     *
     * @param string $size
     * @return integer
     */
    public static function nice_column_to_int($size)
    {
        $widths = GridColumn::get_numeric_widths();
        
        return isset($widths[$size]) ? $widths[$size] : 0;
    }
    
    /**
     * Converts the given integer column width to a CSS class.
     *
     * @param integer $size
     * @return string
     */
    public static function int_column_to_css($size)
    {
        $widths = GridColumn::get_numeric_widths();
        
        if ($width = array_search($size, $widths)) {
            
            return $width . " " . ($size > 1 ? 'columns' : 'column');
            
        }
    }
    
    /**
     * If the given value is numeric, answers an integer, else null.
     *
     * @param mixed $value
     * @return int|null
     */
    public static function integer_or_null($value)
    {
        return is_numeric($value) ? (int) $value : null;
    }
    
    /**
     * Minifies and wraps the given CSS to the specified character width.
     *
     * @param string $css
     * @param integer $wrap
     * @return string
     */
    public static function minify_and_wrap_css($css, $wrap = 200)
    {
        // Remove Comments:
        
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove Space Following Colons:
        
        $css = str_replace(': ', ':', $css);
        
        // Remove Whitespace:
        
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
        
        // Wrap and Answer:
        
        return wordwrap($css, $wrap);
    }
    
    /**
     * Indents the given CSS array to the specified number of spaces.
     *
     * @param array $css
     * @param integer $spaces
     * @return array
     */
    public static function indent_css($css = array(), $spaces = 2)
    {
        $pad = str_pad('', $spaces);
        
        for ($i = 0; $i < count($css); $i++) {
            $css[$i] = $pad . preg_replace("/\n/", "\n" . $pad, $css[$i]);
        }
        
        return $css;
    }
    
    /**
     * Removes empty lines from the given text.
     *
     * @param string $text
     * @return string
     */
    public static function remove_empty_lines($text)
    {
        return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $text);
    }
    
    /**
     * Scans for URLs in the given text and converts them into clickable links.
     *
     * @param string $text
     * @param string $target
     * @return string
     */
    public static function link_urls($text, $target = '_blank')
    {
        $pattern = '!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i';
        
        $replace = '<a href="$1" target="' . $target . '">$1</a>';
        
        return preg_replace($pattern, $replace, $text);
    }
    
    /**
     * Answers the appropriate noun for the given column size.
     *
     * @param string $size
     * @return string
     */
    public static function column_noun($size)
    {
        return in_array($size, array('one', 'one-third', 'two-thirds', 'one-half')) ? 'column' : 'columns';
    }
    
    /**
     * Answers an array of percentage options for a dropdown field.
     *
     * @param integer $start
     * @param integer $end
     * @param integer $increment
     * @return array
     */
    public static function percentage_options($start = 0, $end = 100, $step = 5)
    {
        $options = array();
        
        foreach (range($start, $end, $step) as $i) {
            
            $v = number_format($i / 100, 2);
            
            $options[$v] = $i . '%';
            
        }
        
        return $options;
    }
    
    /**
     * Answers an array containing the descendants of the specified class.
     *
     * @param string $class
     * @return array
     */
    public static function descendants_of($class)
    {
        return SS_ClassLoader::instance()->getManifest()->getDescendantsOf($class);
    }
    
    /**
     * Answers a map of data objects which implement the specified interface.
     *
     * @param string $class
     * @param string $interface
     * @param boolean $html Use HTML formatting.
     * @param boolean $icons Show icons within HTML.
     * @return array
     */
    public static function implementor_map($interface, $class = 'SiteTree', $html = true, $icons = true)
    {
        // Create Map Array:
        
        $map = array();
        
        // Find Implementors:
        
        $implementors = DataObject::get($class)->filter(
            array(
                'ClassName' => ClassInfo::implementorsOf($interface)
            )
        )->sort('ClassName');
        
        // Define Map Array:
        
        foreach ($implementors as $implementor) {
            
            if ($html) {
                
                $map[$implementor->ID] = $implementor->customise(
                    array(
                        'ShowIcons' => $icons
                    )
                )->renderWith('ObjectClassLabel');
                
            } else {
                
                $map[$implementor->ID] = sprintf(
                    '%s (%s)',
                    $implementor->Title,
                    $implementor->i18n_singular_name()
                );
                
            }
            
        }
        
        // Answer Map Array:
        
        return $map;
    }
    
    /**
     * Writes the defaults for the specified class to site configuration.
     *
     * @param SiteConfig Site configuration instance.
     * @param string $class Name of class from which to obtain defaults.
     */
    public static function write_defaults_to_config(SiteConfig $config, $class)
    {
        $defaults = Config::inst()->get($class, 'defaults', Config::FIRST_SET);
        $forcedef = Config::inst()->get($class, 'force_defaults', Config::FIRST_SET);
        
        if (is_array($defaults) && !$config->hasConfigExtension($class)) {
            
            foreach ($defaults as $key => $value) {
                
                if (!$config->$key || $forcedef) {
                    $config->$key = $value;
                }
                
            }
            
            $config->setConfigExtension($class, 1);
            
            $config->write();
            
            DB::alteration_message("Wrote defaults to config for class {$class}", "created");
            
        }
    }
    
    /**
     * Answers the name of the country indicated by the given country code.
     *
     * @param string $code
     * @return string
     */
    public static function get_country_name($code)
    {
        if ($countries = self::get_country_list()) {
            
            if (isset($countries[$code])) {
                
                return $countries[$code];
                
            }
            
        }
    }
    
    /**
     * Answers a list of country names.
     *
     * @return array
     */
    public static function get_country_list()
    {
        return Zend_Locale::getTranslationList('territory', self::get_locale(), 2);
    }
    
    /**
     * Answers the locale of the current user, with a fallback to the i18n locale.
     *
     * @return string
     */
    public static function get_locale()
    {
        if (($Member = Member::currentUser()) && $Member->Locale) {
            
            return $Member->Locale;
            
        }
        
        return i18n::get_locale();
    }
    
    /**
     * Loads the specified JavaScript template (with a provision for including JSON variables).
     *
     * @param string $file
     * @param array $vars
     * @param string $id Uniqueness ID for custom script
     */
    public static function load_javascript_template($file, $vars, $id = null)
    {
        // Load File Contents:
        
        $script = file_get_contents(Director::getAbsFile($file));
        
        // Initialise Search and Replace Arrays:
        
        $s = array();
        $r = array();
        
        // Process Variable Array:
        
        if ($vars) {
            
            foreach ($vars as $k => $v) {
                
                if (strtolower(substr($k, 0, 5)) == 'json:') {
                    $s[] = '$' . substr($k, 5);
                    $r[] = Convert::raw2json($v);
                } else {
                    $s[] = '$' . $k;
                    $r[] = str_replace("\\'", "'", Convert::raw2js($v));
                }
                
            }
            
        }
        
        // Replace Variables in Script:
        
        $script = str_replace($s, $r, $script);
        
        // Load Custom Script:
        
        Requirements::customScript($script, $id);
    }
}
