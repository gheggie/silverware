<?php

/**
 * An extension of the data object class for a SilverWare font.
 */
class SilverWareFont extends DataObject
{
    private static $singular_name = "Font";
    private static $plural_name   = "Fonts";
    
    private static $db = array(
        'Family' => 'Varchar(128)',
        'Styles' => 'Varchar(255)',
        'Fallbacks' => 'Varchar(255)',
        'Disabled' => 'Boolean'
    );
    
    private static $has_one = array(
        'SiteConfig' => 'SiteConfig'
    );
    
    private static $defaults = array(
        'Disabled' => 0
    );
    
    private static $summary_fields = array(
        'FontFamily' => 'Family',
        'StylesNice' => 'Styles',
        'Disabled.Nice' => 'Disabled'
    );
    
    /**
     * Answers an associative array of weight values.
     *
     * @return array
     */
    public static function get_weights()
    {
        return Config::inst()->get(__CLASS__, 'weights');
    }
    
    /**
     * Answers an associative array of weight values, with the number included.
     *
     * @return array
     */
    public static function get_weights_with_number($lowercase = false)
    {
        $weights = self::get_weights();
        
        foreach ($weights as $key => $value) {
            
            if ($lowercase) {
                $value = strtolower($value);
            }
            
            $weights[$key] = $value . ' (' . $key . ')';
            
        }
        
        return $weights;
    }
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Create Field Tab Set:
        
        $fields = FieldList::create(TabSet::create('Root'));
        
        // Create Field Objects:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create(
                    'Family',
                    _t('SilverWareFont.FAMILY', 'Family')
                )->setRightTitle(
                    _t(
                        'SilverWareFont.FAMILYRIGHTTITLE',
                        'Name of font family from Google Fonts, e.g. Roboto.'
                    )
                ),
                CheckboxSetField::create(
                    'Styles',
                    _t('SilverWareFont.STYLES', 'Styles'),
                    $this->getFontStyleOptions()
                ),
                TextField::create(
                    'Fallbacks',
                    _t('SilverWareFont.FALLBACKS', 'Fallbacks')
                )->setRightTitle(
                    _t(
                        'SilverWareFont.FALLBACKSRIGHTTITLE',
                        'Comma separated list of fallback fonts, e.g. Arial, Helvetica, sans-serif.'
                    )
                ),
                CheckboxField::create('Disabled', _t('SilverWareFont.DISABLED', 'Disabled'))
            )
        );
        
        // Extend Field Objects:
        
        $this->extend('updateCMSFields', $fields);
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Creates any required default records (if they do not already exist).
     */
    public function requireDefaultRecords()
    {
        // Require Default Records (from parent):
        
        parent::requireDefaultRecords();
        
        // Obtain Site Configuration:
        
        $config = SiteConfig::current_site_config();
        
        // Create Default Records:
        
        if (!self::get()->exists()) {
            
            // Create Default Font:
            
            $font = SilverWareFont::create();
            
            $font->Family = "Roboto";
            $font->Styles = "300,300italic,400,400italic,500,500italic,700,700italic";
            $font->Fallbacks = "Arial, Helvetica, sans-serif";
            $font->SiteConfigID = $config->ID;
            
            $font->write();
            
            // Create Monospace Font:
            
            $mono = SilverWareFont::create();
            
            $mono->Family = "Droid Sans Mono";
            $mono->Styles = "400";
            $mono->Fallbacks = "Consolas, Courier, monospace";
            $mono->SiteConfigID = $config->ID;
            
            $mono->write();
            
            // Define Template Fonts:
            
            if (!$config->SilverWareBodyFontID) {
                $config->SilverWareBodyFontID = $font->ID;
            }
            
            if (!$config->SilverWareHeadingFont) {
                $config->SilverWareHeadingFontID = $font->ID;
            }
            
            if (!$config->SilverWareMonospaceFont) {
                $config->SilverWareMonospaceFontID = $mono->ID;
            }
            
            $config->write();
            
            DB::alteration_message('Default SilverWare fonts created', 'created');
            
        }
        
    }
    
    /**
     * Answers the URL to the font resource.
     *
     * @return string
     */
    public function getURL()
    {
        if ($this->Family && $this->Styles) {
            
            return "//fonts.googleapis.com/css?" . http_build_query(array('family' => $this->getFamilyParam()));
            
        }
    }
    
    /**
     * Answers the title of the receiver for the CMS interface.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getFontFamily();
    }
    
    /**
     * Answers the value of the styles attribute with nice formatting.
     *
     * @return string
     */
    public function getStylesNice()
    {
        return str_replace(',', ', ', $this->Styles);
    }
    
    /**
     * Answers the font-family string.
     *
     * @param boolean $quotes Wrap family in quotes?
     * @return string
     */
    public function getFontFamily($quotes = false)
    {
        // Create Fonts Array:
        
        $fonts = array();
        
        // Define Family:
        
        if ($this->Family) {
            
            $fonts[] = ($quotes ? "'" : "") . $this->Family . ($quotes ? "'" : "");
            
        }
        
        // Define Fallbacks:
        
        if ($this->Fallbacks) {
            
            $fonts[] = $this->Fallbacks;
            
        }
        
        // Answer Fonts String:
        
        return implode(', ', $fonts);
    }
    
    /**
     * Answers the font-family CSS property.
     *
     * @return string
     */
    public function getStyleFontFamily()
    {
        return $this->getFontFamily(true);
    }
    
    /**
     * Answers the family parameter for the font resource URL.
     *
     * @return string
     */
    protected function getFamilyParam()
    {
        if ($this->Family && $this->Styles) {
            
            return $this->Family . ":" . $this->Styles;
            
        }
    }
    
    /**
     * Answers an array of font style options for the CMS interface.
     *
     * @return array
     */
    protected function getFontStyleOptions()
    {
        $options = array();
        
        for ($i = 100; $i <= 900; $i += 100) {
            
            $w = $this->config()->weights[$i];
            
            $options["{$i}"]       = "{$w} {$i}";
            $options["{$i}italic"] = "{$w} {$i} Italic";
            
        }
        
        return $options;
    }
}
