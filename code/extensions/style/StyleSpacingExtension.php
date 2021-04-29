<?php

/**
 * An extension of the SilverWare style extension class to apply spacing styles to the extended object.
 */
class StyleSpacingExtension extends SilverWareStyleExtension
{
    private static $db = array(
        
        'StyleMargin' => 'Varchar(16)',
        'StyleMarginTop' => 'Varchar(16)',
        'StyleMarginLeft' => 'Varchar(16)',
        'StyleMarginRight' => 'Varchar(16)',
        'StyleMarginBottom' => 'Varchar(16)',
        
        'StyleMarginUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleMarginTopUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleMarginLeftUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleMarginRightUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleMarginBottomUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        
        'StylePadding' => 'Varchar(16)',
        'StylePaddingTop' => 'Varchar(16)',
        'StylePaddingLeft' => 'Varchar(16)',
        'StylePaddingRight' => 'Varchar(16)',
        'StylePaddingBottom' => 'Varchar(16)',
        
        'StylePaddingUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StylePaddingTopUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StylePaddingLeftUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StylePaddingRightUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StylePaddingBottomUnit' => "Enum('px, em, rem, pt, cm, in', 'px')"
        
    );
    
    private static $defaults = array(
        
        'StyleMarginUnit' => 'px',
        'StyleMarginTopUnit' => 'px',
        'StyleMarginLeftUnit' => 'px',
        'StyleMarginRightUnit' => 'px',
        'StyleMarginBottomUnit' => 'px',
        
        'StylePaddingUnit' => 'px',
        'StylePaddingTopUnit' => 'px',
        'StylePaddingLeftUnit' => 'px',
        'StylePaddingRightUnit' => 'px',
        'StylePaddingBottomUnit' => 'px'
        
    );
    
    protected $css = array(
        
        'margin' => 'getStyleMarginCSS',
        
        'margin-top' => 'getStyleMarginTopCSS',
        'margin-left' => 'getStyleMarginLeftCSS',
        'margin-right' => 'getStyleMarginRightCSS',
        'margin-bottom' => 'getStyleMarginBottomCSS',
        
        'padding' => 'getStylePaddingCSS',
        
        'padding-top' => 'getStylePaddingTopCSS',
        'padding-left' => 'getStylePaddingLeftCSS',
        'padding-right' => 'getStylePaddingRightCSS',
        'padding-bottom' => 'getStylePaddingBottomCSS'
        
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
                'StyleSpacingToggle',
                _t('StyleSpacingExtension.SPACING', 'Spacing'),
                array(
                    FieldGroup::create(
                        _t('StyleSpacingExtension.MARGIN', 'Margin'),
                        array(
                            TextField::create('StyleMargin', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.ALL', 'All')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleMarginUnit',
                                '',
                                $this->owner->dbObject('StyleMarginUnit')->enumValues()
                            ),
                            TextField::create('StyleMarginTop', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.TOP', 'Top')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleMarginTopUnit',
                                '',
                                $this->owner->dbObject('StyleMarginTopUnit')->enumValues()
                            ),
                            TextField::create('StyleMarginLeft', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.LEFT', 'Left')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleMarginLeftUnit',
                                '',
                                $this->owner->dbObject('StyleMarginLeftUnit')->enumValues()
                            ),
                            TextField::create('StyleMarginRight', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.RIGHT', 'Right')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleMarginRightUnit',
                                '',
                                $this->owner->dbObject('StyleMarginRightUnit')->enumValues()
                            ),
                            TextField::create('StyleMarginBottom', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.BOTTOM', 'Bottom')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleMarginBottomUnit',
                                '',
                                $this->owner->dbObject('StyleMarginBottomUnit')->enumValues()
                            )
                        )
                    ),
                    FieldGroup::create(
                        _t('StyleSpacingExtension.PADDING', 'Padding'),
                        array(
                            TextField::create('StylePadding', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.ALL', 'All')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StylePaddingUnit',
                                '',
                                $this->owner->dbObject('StylePaddingUnit')->enumValues()
                            ),
                            TextField::create('StylePaddingTop', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.TOP', 'Top')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StylePaddingTopUnit',
                                '',
                                $this->owner->dbObject('StylePaddingTopUnit')->enumValues()
                            ),
                            TextField::create('StylePaddingLeft', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.LEFT', 'Left')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StylePaddingLeftUnit',
                                '',
                                $this->owner->dbObject('StylePaddingLeftUnit')->enumValues()
                            ),
                            TextField::create('StylePaddingRight', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.RIGHT', 'Right')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StylePaddingRightUnit',
                                '',
                                $this->owner->dbObject('StylePaddingRightUnit')->enumValues()
                            ),
                            TextField::create('StylePaddingBottom', '')->setAttribute(
                                'placeholder',
                                _t('StyleSpacingExtension.BOTTOM', 'Bottom')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StylePaddingBottomUnit',
                                '',
                                $this->owner->dbObject('StylePaddingBottomUnit')->enumValues()
                            )
                        )
                    )
                )
            )
        );
        
    }
    
    /**
     * Answers true if the extended object has all margin values defined.
     *
     * @return boolean
     */
    public function hasAllMargins()
    {
        if ($this->owner->StyleMargin != '') {
            return true;
        }
        
        return (
            $this->owner->StyleMarginTop != '' &&
            $this->owner->StyleMarginLeft != '' &&
            $this->owner->StyleMarginRight != '' &&
            $this->owner->StyleMarginBottom != ''
        );
    }
    
    /**
     * Answers true if the extended object has all padding values defined.
     *
     * @return boolean
     */
    public function hasAllPaddings()
    {
        if ($this->owner->StylePadding != '') {
            return true;
        }
        
        return (
            $this->owner->StylePaddingTop != '' &&
            $this->owner->StylePaddingLeft != '' &&
            $this->owner->StylePaddingRight != '' &&
            $this->owner->StylePaddingBottom != ''
        );
    }
    
    /**
     * Defines the margin of the extended object by parsing the given value.
     *
     * @param string $value
     * @return DataObject
     */
    public function setStyleMargin($value)
    {
        return $this->setSizeFromValue($value, 'StyleMargin');
    }
    
    /**
     * Defines the padding of the extended object by parsing the given value.
     *
     * @param string $value
     * @return DataObject
     */
    public function setStylePadding($value)
    {
        return $this->setSizeFromValue($value, 'StylePadding');
    }
    
    /**
     * Answers the CSS string for the margin style.
     *
     * @return string
     */
    public function getStyleMarginCSS()
    {
        $css = array();
        
        if ($this->hasAllMargins()) {
            
            if ($this->owner->StyleMargin != '') {
                
                $css[] = $this->owner->StyleMargin . $this->owner->StyleMarginUnit;
                
            } else {
                
                $css[] = $this->owner->StyleMarginTop . $this->owner->StyleMarginTopUnit;
                $css[] = $this->owner->StyleMarginRight . $this->owner->StyleMarginRightUnit;
                $css[] = $this->owner->StyleMarginBottom . $this->owner->StyleMarginBottomUnit;
                $css[] = $this->owner->StyleMarginLeft . $this->owner->StyleMarginLeftUnit;
                
            }
            
        }
        
        return implode(' ', $css);
    }
    
    /**
     * Answers the CSS string for the padding style.
     *
     * @return string
     */
    public function getStylePaddingCSS()
    {
        $css = array();
        
        if ($this->hasAllPaddings()) {
            
            if ($this->owner->StylePadding != '') {
                
                $css[] = $this->owner->StylePadding . $this->owner->StylePaddingUnit;
                
            } else {
                
                $css[] = $this->owner->StylePaddingTop . $this->owner->StylePaddingTopUnit;
                $css[] = $this->owner->StylePaddingRight . $this->owner->StylePaddingRightUnit;
                $css[] = $this->owner->StylePaddingBottom . $this->owner->StylePaddingBottomUnit;
                $css[] = $this->owner->StylePaddingLeft . $this->owner->StylePaddingLeftUnit;
                
            }
            
        }
        
        return implode(' ', $css);
    }
    
    /**
     * Answers the CSS string for the margin-top style.
     *
     * @return string
     */
    public function getStyleMarginTopCSS()
    {
        if ($this->owner->StyleMarginTop != '' && !$this->hasAllMargins()) {
            return $this->owner->StyleMarginTop . $this->owner->StyleMarginTopUnit;
        }
    }
    
    /**
     * Answers the CSS string for the margin-left style.
     *
     * @return string
     */
    public function getStyleMarginLeftCSS()
    {
        if ($this->owner->StyleMarginLeft != '' && !$this->hasAllMargins()) {
            return $this->owner->StyleMarginLeft . $this->owner->StyleMarginLeftUnit;
        }
    }
    
    /**
     * Answers the CSS string for the margin-right style.
     *
     * @return string
     */
    public function getStyleMarginRightCSS()
    {
        if ($this->owner->StyleMarginRight != '' && !$this->hasAllMargins()) {
            return $this->owner->StyleMarginRight . $this->owner->StyleMarginRightUnit;
        }
    }
    
    /**
     * Answers the CSS string for the margin-bottom style.
     *
     * @return string
     */
    public function getStyleMarginBottomCSS()
    {
        if ($this->owner->StyleMarginBottom != '' && !$this->hasAllMargins()) {
            return $this->owner->StyleMarginBottom . $this->owner->StyleMarginBottomUnit;
        }
    }
    
    /**
     * Answers the CSS string for the padding-top style.
     *
     * @return string
     */
    public function getStylePaddingTopCSS()
    {
        if ($this->owner->StylePaddingTop != '' && !$this->hasAllPaddings()) {
            return $this->owner->StylePaddingTop . $this->owner->StylePaddingTopUnit;
        }
    }
    
    /**
     * Answers the CSS string for the padding-left style.
     *
     * @return string
     */
    public function getStylePaddingLeftCSS()
    {
        if ($this->owner->StylePaddingLeft != '' && !$this->hasAllPaddings()) {
            return $this->owner->StylePaddingLeft . $this->owner->StylePaddingLeftUnit;
        }
    }
    
    /**
     * Answers the CSS string for the padding-right style.
     *
     * @return string
     */
    public function getStylePaddingRightCSS()
    {
        if ($this->owner->StylePaddingRight != '' && !$this->hasAllPaddings()) {
            return $this->owner->StylePaddingRight . $this->owner->StylePaddingRightUnit;
        }
    }
    
    /**
     * Answers the CSS string for the padding-bottom style.
     *
     * @return string
     */
    public function getStylePaddingBottomCSS()
    {
        if ($this->owner->StylePaddingBottom != '' && !$this->hasAllPaddings()) {
            return $this->owner->StylePaddingBottom . $this->owner->StylePaddingBottomUnit;
        }
    }
}
