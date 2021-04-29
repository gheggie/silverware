<?php

/**
 * An extension of the SilverWare style extension class to apply border styles to the extended object.
 */
class StyleBorderExtension extends SilverWareStyleExtension
{
    private static $db = array(
        
        'StyleBorderWidth' => 'Varchar(16)',
        'StyleBorderTopWidth' => 'Varchar(16)',
        'StyleBorderLeftWidth' => 'Varchar(16)',
        'StyleBorderRightWidth' => 'Varchar(16)',
        'StyleBorderBottomWidth' => 'Varchar(16)',
        
        'StyleBorderUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleBorderTopUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleBorderLeftUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleBorderRightUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleBorderBottomUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        
        'StyleBorderStyle' => "Enum('none, solid, dashed, dotted, double', 'none')",
        'StyleBorderTopStyle' => "Enum('none, solid, dashed, dotted, double', 'none')",
        'StyleBorderLeftStyle' => "Enum('none, solid, dashed, dotted, double', 'none')",
        'StyleBorderRightStyle' => "Enum('none, solid, dashed, dotted, double', 'none')",
        'StyleBorderBottomStyle' => "Enum('none, solid, dashed, dotted, double', 'none')",
        
        'StyleBorderColor' => 'Color',
        'StyleBorderAlpha' => 'Decimal(3,2,1)',
        'StyleBorderTopColor' => 'Color',
        'StyleBorderTopAlpha' => 'Decimal(3,2,1)',
        'StyleBorderLeftColor' => 'Color',
        'StyleBorderLeftAlpha' => 'Decimal(3,2,1)',
        'StyleBorderRightColor' => 'Color',
        'StyleBorderRightAlpha' => 'Decimal(3,2,1)',
        'StyleBorderBottomColor' => 'Color',
        'StyleBorderBottomAlpha' => 'Decimal(3,2,1)',
        
        'StyleBorderRadius' => 'Varchar(16)',
        'StyleBorderRadiusUnit' => "Enum('px, em, rem, pt, cm, in', 'px')"
        
    );
    
    private static $defaults = array(
        
        'StyleBorderUnit' => 'px',
        'StyleBorderTopUnit' => 'px',
        'StyleBorderLeftUnit' => 'px',
        'StyleBorderRightUnit' => 'px',
        'StyleBorderBottomUnit' => 'px',
        
        'StyleBorderAlpha' => 1,
        'StyleBorderTopAlpha' => 1,
        'StyleBorderLeftAlpha' => 1,
        'StyleBorderRightAlpha' => 1,
        'StyleBorderBottomAlpha' => 1,
        
        'StyleBorderStyle' => 'none',
        'StyleBorderTopStyle' => 'none',
        'StyleBorderLeftStyle' => 'none',
        'StyleBorderRightStyle' => 'none',
        'StyleBorderBottomStyle' => 'none',
        
        'StyleBorderRadiusUnit' => 'px'
        
    );
    
    protected $css = array(
        
        'border' => 'getStyleBorderCSS',
        'border-top' => 'getStyleBorderTopCSS',
        'border-left' => 'getStyleBorderLeftCSS',
        'border-right' => 'getStyleBorderRightCSS',
        'border-bottom' => 'getStyleBorderBottomCSS',
        
        '-webkit-border-radius' => 'getStyleBorderRadiusCSS',
        '-moz-border-radius' => 'getStyleBorderRadiusCSS',
        'border-radius' => 'getStyleBorderRadiusCSS'
        
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'StyleBorderToggle',
                _t('StyleBorderExtension.BORDER', 'Border'),
                array(
                    $this->getBorderFieldGroup(),
                    $this->getBorderFieldGroup('top'),
                    $this->getBorderFieldGroup('left'),
                    $this->getBorderFieldGroup('right'),
                    $this->getBorderFieldGroup('bottom'),
                    FieldGroup::create(
                        'Border radius',
                        array(
                            TextField::create('StyleBorderRadius', '')->setAttribute(
                                'placeholder',
                                _t('StyleBorderExtension.SIZE', 'Size')
                            ),
                            DropdownField::create(
                                'StyleBorderRadiusUnit',
                                '',
                                $this->owner->dbObject('StyleBorderRadiusUnit')->enumValues()
                            )
                        )
                    )
                )
            )
        );
    }
    
    /**
     * Answers the CSS string for the border style.
     *
     * @return string
     */
    public function getStyleBorderCSS()
    {
        return $this->getBorderStyle();
    }
    
    /**
     * Answers the CSS string for the border-top style.
     *
     * @return string
     */
    public function getStyleBorderTopCSS()
    {
        return $this->getBorderStyle('top');
    }
    
    /**
     * Answers the CSS string for the border-left style.
     *
     * @return string
     */
    public function getStyleBorderLeftCSS()
    {
        return $this->getBorderStyle('left');
    }
    
    /**
     * Answers the CSS string for the border-right style.
     *
     * @return string
     */
    public function getStyleBorderRightCSS()
    {
        return $this->getBorderStyle('right');
    }
    
    /**
     * Answers the CSS string for the border-bottom style.
     *
     * @return string
     */
    public function getStyleBorderBottomCSS()
    {
        return $this->getBorderStyle('bottom');
    }
    
    /**
     * Answers the CSS string for the border-radius style.
     *
     * @return string
     */
    public function getStyleBorderRadiusCSS()
    {
        $css = array();
        
        if ($this->owner->StyleBorderRadius != '') {
            
            $css[] = $this->owner->StyleBorderRadius . $this->owner->StyleBorderRadiusUnit;
            
        }
        
        return implode(' ', $css);
    }
    
    /**
     * Answers the CSS string for the specified border type.
     *
     * @param string $type
     * @return string
     */
    protected function getBorderStyle($type = null)
    {
        $css = array();
        
        $type_camel = ucfirst(strtolower($type));
        
        $width = "StyleBorder{$type_camel}Width";
        $style = "StyleBorder{$type_camel}Style";
        $color = "StyleBorder{$type_camel}Color";
        $alpha = "StyleBorder{$type_camel}Alpha";
        $unit  = "StyleBorder{$type_camel}Unit";
        
        if ($this->owner->{$width} != '') {
            
            $css[] = $this->owner->{$width} . $this->owner->{$unit};
            $css[] = $this->owner->{$style};
            
            if ($this->owner->{$color} != '') {
                $css[] = $this->owner->dbObject($color)->CSSColor($this->owner->{$alpha});
            } else {
                $css[] = "transparent";
            }
            
        }
        
        return implode(' ', $css);
    }
    
    /**
     * Answers a field group for defining a border.
     *
     * @param string $type
     * @return FieldGroup
     */
    protected function getBorderFieldGroup($type = null)
    {
        // Define Variables:
        
        $type_lower = strtolower($type);
        $type_upper = strtoupper($type);
        $type_camel = ucfirst($type_lower);
        
        // Obtain Alpha Options:
        
        $alpha = SilverWareTools::percentage_options(100, 0, 5);
        
        // Answer Field Group:
        
        return FieldGroup::create(
            _t("StyleBorderExtension.BORDER{$type_upper}", "Border {$type_lower}"),
            array(
                TextField::create("StyleBorder{$type_camel}Width", '')->setAttribute(
                    'placeholder',
                    _t('StyleBorderExtension.WIDTH', 'Width')
                )->setMaxLength(8),
                DropdownField::create(
                    "StyleBorder{$type_camel}Unit",
                    '',
                    $this->owner->dbObject("StyleBorder{$type_camel}Unit")->enumValues()
                ),
                DropdownField::create(
                    "StyleBorder{$type_camel}Style",
                    '',
                    $this->owner->dbObject("StyleBorder{$type_camel}Style")->enumValues()
                ),
                ColorField::create("StyleBorder{$type_camel}Color", '')->setAttribute(
                    'placeholder',
                    _t('StyleBorderExtension.COLOR', 'Color')
                ),
                DropdownField::create("StyleBorder{$type_camel}Alpha", '', $alpha)
            )
        );
    }
}
