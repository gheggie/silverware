<?php

/**
 * An extension of the SilverWare style extension class to apply text styles to the extended object.
 */
class StyleTextExtension extends SilverWareStyleExtension
{
    private static $db = array(
        'StyleTextColor' => 'Color',
        'StyleTextAlpha' => 'Decimal(3,2,1)',
        'StyleTextInherit' => 'Boolean',
        'StyleFontSize' => 'Varchar(16)',
        'StyleFontSizeUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')",
        'StyleFontWeight' => 'Varchar(16)',
        'StyleLineHeight' => 'Varchar(16)',
        'StyleLineHeightUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')",
        'StyleFontStyle' => "Enum('default, normal, italic, oblique', 'default')",
        'StyleTextAlignment' => "Enum('default, left, center, right, justify', 'default')",
        'StyleTextTransform' => "Enum('default, none, uppercase, lowercase, capitalize', 'default')",
        'StyleTextDecoration' => "Enum('default, none, underline, overline, line-through', 'default')",
        'StyleLetterSpacing' => 'Varchar(16)',
        'StyleLetterSpacingUnit' => "Enum('px, em, rem, pt, cm, in', 'px')"
    );
    
    private static $has_one = array(
        'StyleFont' => 'SilverWareFont'
    );
    
    private static $defaults = array(
        'StyleTextAlpha' => 1,
        'StyleTextInherit' => 0,
        'StyleFontSizeUnit' => 'rem',
        'StyleLineHeightUnit' => 'rem',
        'StyleFontStyle' => 'default',
        'StyleTextAlignment' => 'default',
        'StyleTextTransform' => 'default',
        'StyleTextDecoration' => 'default',
        'StyleLetterSpacingUnit' => 'px'
    );
    
    protected $css = array(
        'color' => 'getStyleTextColorCSS',
        'font-size' => 'getStyleFontSizeCSS',
        'font-style' => 'getStyleFontStyleCSS',
        'font-family' => 'getStyleFontFamilyCSS',
        'font-weight' => 'getStyleFontWeightCSS',
        'line-height' => 'getStyleLineHeightCSS',
        'text-align' => 'getStyleTextAlignmentCSS',
        'letter-spacing' => 'getStyleLetterSpacingCSS',
        'text-transform' => 'getStyleTextTransformCSS',
        'text-decoration' => 'getStyleTextDecorationCSS'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Obtain Alpha Options:
        
        $alpha = SilverWareTools::percentage_options(100, 0, 5);
        
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'StyleTextToggle',
                _t('StyleTextExtension.TEXT', 'Text'),
                array(
                    FieldGroup::create(
                        _t('StyleTextExtension.TEXTCOLOR', 'Text color'),
                        array(
                            ColorField::create('StyleTextColor', ''),
                            DropdownField::create('StyleTextAlpha', '', $alpha),
                            CheckboxField::create(
                                'StyleTextInherit',
                                _t('StyleTextExtension.INHERIT', 'Inherit')
                            )
                        )
                    ),
                    DropdownField::create(
                        'StyleFontID',
                        _t('StyleTextExtension.FONT', 'Font'),
                        SilverWareFont::get()->map()
                    )->setEmptyString(' '),
                    FieldGroup::create(
                        _t('StyleTextExtension.FONTSIZE', 'Font size'),
                        array(
                            TextField::create(
                                'StyleFontSize',
                                ''
                            )->setAttribute(
                                'placeholder',
                                _t('StyleTextExtension.SIZE', 'Size')
                            ),
                            DropdownField::create(
                                'StyleFontSizeUnit',
                                '',
                                $this->owner->dbObject('StyleFontSizeUnit')->enumValues()
                            )
                        )
                    ),
                    FieldGroup::create(
                        _t('StyleTextExtension.LINEHEIGHT', 'Line height'),
                        array(
                            TextField::create(
                                'StyleLineHeight',
                                ''
                            )->setAttribute(
                                'placeholder',
                                _t('StyleTextExtension.HEIGHT', 'Height')
                            ),
                            DropdownField::create(
                                'StyleLineHeightUnit',
                                '',
                                $this->owner->dbObject('StyleLineHeightUnit')->enumValues()
                            )
                        )
                    ),
                    FieldGroup::create(
                        _t('StyleTextExtension.LETTERSPACING', 'Letter spacing'),
                        array(
                            TextField::create(
                                'StyleLetterSpacing',
                                ''
                            )->setAttribute(
                                'placeholder',
                                _t('StyleTextExtension.SPACING', 'Spacing')
                            ),
                            DropdownField::create(
                                'StyleLetterSpacingUnit',
                                '',
                                $this->owner->dbObject('StyleLetterSpacingUnit')->enumValues()
                            )
                        )
                    ),
                    DropdownField::create(
                        'StyleFontStyle',
                        _t('StyleTextExtension.FONTSTYLE', 'Font style'),
                        $this->owner->dbObject('StyleFontStyle')->enumValues()
                    ),
                    DropdownField::create(
                        'StyleFontWeight',
                        _t('StyleTextExtension.FONTWEIGHT', 'Font weight'),
                        SilverWareFont::get_weights_with_number(true)
                    )->setEmptyString(_t('StyleTextExtension.DEFAULTLOWERCASE', 'default')),
                    DropdownField::create(
                        'StyleTextAlignment',
                        _t('StyleTextExtension.TEXTALIGNMENT', 'Text alignment'),
                        $this->owner->dbObject('StyleTextAlignment')->enumValues()
                    ),
                    DropdownField::create(
                        'StyleTextTransform',
                        _t('StyleTextExtension.TEXTTRANSFORM', 'Text transform'),
                        $this->owner->dbObject('StyleTextTransform')->enumValues()
                    ),
                    DropdownField::create(
                        'StyleTextDecoration',
                        _t('StyleTextExtension.TEXTDECORATION', 'Text decoration'),
                        $this->owner->dbObject('StyleTextDecoration')->enumValues()
                    )
                )
            )
        );
    }
    
    /**
     * Defines the font-size of the extended object by parsing the given value.
     *
     * @param string $value
     * @return DataObject
     */
    public function setStyleFontSize($value)
    {
        return $this->setSizeProperty($value, 'StyleFontSize');
    }
    
    /**
     * Defines the line-height of the extended object by parsing the given value.
     *
     * @param string $value
     * @return DataObject
     */
    public function setStyleLineHeight($value)
    {
        return $this->setSizeProperty($value, 'StyleLineHeight');
    }
    
    /**
     * Defines the letter-spacing of the extended object by parsing the given value.
     *
     * @param string $value
     * @return DataObject
     */
    public function setStyleLetterSpacing($value)
    {
        return $this->setSizeProperty($value, 'StyleLetterSpacing');
    }
    
    /**
     * Answers the CSS string for the color style.
     *
     * @return string
     */
    public function getStyleTextColorCSS()
    {
        if ($this->owner->StyleTextInherit) {
            return "inherit";
        }
        
        if ($this->owner->StyleTextColor != '') {
            
            $alpha = $this->owner->StyleTextAlpha;
            $color = $this->owner->dbObject('StyleTextColor');
            
            return $color->CSSColor($alpha);
            
        }
    }
    
    /**
     * Answers the font-family from the associated font object.
     *
     * @return string
     */
    public function getStyleFontFamilyCSS()
    {
        if ($this->owner->StyleFontID) {
            
            return $this->owner->StyleFont()->getStyleFontFamily();
            
        }
    }
    
    /**
     * Answers the CSS string for the font-size style.
     *
     * @return string
     */
    public function getStyleFontSizeCSS()
    {
        if ($this->owner->StyleFontSize) {
            
            return $this->owner->StyleFontSize . $this->owner->StyleFontSizeUnit;
            
        }
    }
    
    /**
     * Answers the CSS string for the font-style style.
     *
     * @return string
     */
    public function getStyleFontStyleCSS()
    {
        if ($this->owner->StyleFontStyle != 'default') {
            
            return $this->owner->StyleFontStyle;
            
        }
    }
    
    /**
     * Answers the CSS string for the font-weight style.
     *
     * @return string
     */
    public function getStyleFontWeightCSS()
    {
        if ($this->owner->StyleFontWeight) {
            
            return $this->owner->StyleFontWeight;
            
        }
    }
    
    /**
     * Answers the CSS string for the line-height style.
     *
     * @return string
     */
    public function getStyleLineHeightCSS()
    {
        if ($this->owner->StyleLineHeight) {
            
            return $this->owner->StyleLineHeight . $this->owner->StyleLineHeightUnit;
            
        }
    }
    
    /**
     * Answers the CSS string for the letter-spacing style.
     *
     * @return string
     */
    public function getStyleLetterSpacingCSS()
    {
        if ($this->owner->StyleLetterSpacing) {
            
            return $this->owner->StyleLetterSpacing . $this->owner->StyleLetterSpacingUnit;
            
        }
    }
    
    /**
     * Answers the CSS string for the text-align style.
     *
     * @return string
     */
    public function getStyleTextAlignmentCSS()
    {
        if ($this->owner->StyleTextAlignment != 'default') {
            
            return $this->owner->StyleTextAlignment;
            
        }
    }
    
    /**
     * Answers the CSS string for the text-transform style.
     *
     * @return string
     */
    public function getStyleTextTransformCSS()
    {
        if ($this->owner->StyleTextTransform != 'default') {
            
            return $this->owner->StyleTextTransform;
            
        }
    }
    
    /**
     * Answers the CSS string for the text-decoration style.
     *
     * @return string
     */
    public function getStyleTextDecorationCSS()
    {
        if ($this->owner->StyleTextDecoration != 'default') {
            
            return $this->owner->StyleTextDecoration;
            
        }
    }
}
