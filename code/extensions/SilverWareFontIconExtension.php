<?php

/**
 * An extension of the data extension class which allows extended objects to use Font Awesome icons.
 */
class SilverWareFontIconExtension extends DataExtension
{
    private static $db = array(
        'FontIcon' => 'Varchar(64)',
        'FontIconSize' => 'Varchar(8)',
        'FontIconColor' => 'Color',
        'FontIconAlpha' => 'Decimal(3,2,1)'
    );
    
    private static $defaults = array(
        'FontIconAlpha' => 1
    );
    
    private static $summary_fields = array(
        'FontIconTagCMS' => 'Icon'
    );
    
    /**
     * Answers a map of Font Awesome icons for use in a dropdown field.
     *
     * @param array $icons
     * @return array
     */
    public static function get_icon_map($icons = array())
    {
        $map = array();
        
        if (empty($icons)) {
            $icons = Config::inst()->get('SilverWareFontIconExtension', 'fa_icons');
        }
        
        foreach ($icons as $icon) {
            $icon = preg_replace('/^fa\-/', '', $icon);
            $map["fa-{$icon}"] = $icon;
        }
        
        return $map;
    }
    
    /**
     * Answers a map of Font Awesome icon sizes for use in a dropdown field.
     *
     * @return array
     */
    public static function get_icon_size_map()
    {
        return Config::inst()->get('SilverWareFontIconExtension', 'fa_sizes');
    }
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Obtain Alpha Options:
        
        $alpha = SilverWareTools::percentage_options(100, 0, 5);
        
        // Insert Icon Tab:
        
        $fields->insertAfter(
            Tab::create(
                'Icon',
                _t('SilverWareFontIconExtension.ICON', 'Icon')
            ),
            'Main'
        );
        
        // Create Icon Fields:
        
        $fields->addFieldsToTab(
            'Root.Icon',
            array(
                IconDropdownField::create(
                    'FontIcon',
                    _t('SilverWareFontIconExtension.ICON', 'Icon'),
                    self::get_icon_map()
                )->setEmptyString(' '),
                DropdownField::create(
                    'FontIconSize',
                    _t('SilverWareFontIconExtension.ICONSIZE', 'Icon size'),
                    self::get_icon_size_map()
                )->setEmptyString(' '),
                FieldGroup::create(
                    _t('SilverWareFontIconExtension.ICONCOLOR', 'Icon color'),
                    array(
                        ColorField::create('FontIconColor', ''),
                        DropdownField::create('FontIconAlpha', '', $alpha)
                    )
                )->addExtraClass('font-icon-color')
            )
        );
    }
    
    /**
     * Updates the summary fields of the extended object.
     *
     * @param array $fields
     */
    public function updateSummaryFields(&$fields)
    {
        if ($summary_fields = Config::inst()->get($this->class, 'summary_fields')) {
            
            $fields = array_merge($summary_fields, $fields);
            
        }
    }
    
    /**
     * Answers a string of Font Awesome icon class names for the HTML template.
     *
     * @return string
     */
    public function getFontIconClass()
    {
        return implode(' ', $this->getFontIconClassNames());
    }
    
    /**
     * Answers an array of Font Awesome icon class names for the receiver.
     *
     * @return array
     */
    public function getFontIconClassNames()
    {
        $classes = array();
        
        if ($this->owner->FontIcon) {
            
            $classes[] = "fa";
            
            $classes[] = $this->owner->FontIcon;
            
            if ($this->owner->FontIconSize) {
                $classes[] = $this->owner->FontIconSize;
            }
            
            if ($this->owner->FontIconListItem) {
                $classes[] = "fa-li";
            }
            
            if ($this->owner->FontIconFixedWidth) {
                $classes[] = "fa-fw";
            }
            
        }
        
        return $classes;
    }
    
    /**
     * Answers true if the extended object has a Font Awesome icon defined.
     *
     * @return boolean
     */
    public function HasFontIcon()
    {
        return (boolean) $this->owner->FontIcon;
    }
    
    /**
     * Answers true if the extended object has a Font Awesome icon color defined.
     *
     * @return boolean
     */
    public function HasFontIconColor()
    {
        return ($this->owner->FontIconColor != '');
    }
    
    /**
     * Answers the CSS string for the font icon color style.
     *
     * @return string
     */
    public function getFontIconColorCSS()
    {
        if ($this->owner->HasFontIconColor()) {
            
            return $this->owner->dbObject('FontIconColor')->CSSColor($this->owner->FontIconAlpha);
            
        }
    }
    
    /**
     * Renders the icon tag for the HTML template.
     *
     * @return string
     */
    public function FontIconTag()
    {
        return $this->owner->renderWith('FontIconTag');
    }
    
    /**
     * Renders the icon tag for the CMS.
     *
     * @return string
     */
    public function FontIconTagCMS()
    {
        return $this->owner->customise(
            array(
                'FontIconClass' => 'fa fa-fw ' . $this->owner->FontIcon
            )
        )->renderWith('FontIconTagCMS');
    }
}
