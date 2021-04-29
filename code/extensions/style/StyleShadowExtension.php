<?php

/**
 * An extension of the SilverWare style extension class to apply shadow styles to the extended object.
 */
class StyleShadowExtension extends SilverWareStyleExtension
{
    private static $db = array(
        
        'StyleBoxShadowColor' => 'Color',
        'StyleBoxShadowAlpha' => 'Decimal(3,2,1)',
        'StyleBoxShadowInset' => 'Boolean',
        'StyleBoxShadowOffsetX' => 'Varchar(16)',
        'StyleBoxShadowOffsetXUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleBoxShadowOffsetY' => 'Varchar(16)',
        'StyleBoxShadowOffsetYUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleBoxShadowBlurRadius' => 'Varchar(16)',
        'StyleBoxShadowBlurRadiusUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleBoxShadowSpreadRadius' => 'Varchar(16)',
        'StyleBoxShadowSpreadRadiusUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        
        'StyleTextShadowColor' => 'Color',
        'StyleTextShadowAlpha' => 'Decimal(3,2,1)',
        'StyleTextShadowOffsetX' => 'Varchar(16)',
        'StyleTextShadowOffsetXUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleTextShadowOffsetY' => 'Varchar(16)',
        'StyleTextShadowOffsetYUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'StyleTextShadowBlurRadius' => 'Varchar(16)',
        'StyleTextShadowBlurRadiusUnit' => "Enum('px, em, rem, pt, cm, in', 'px')"
        
    );
    
    private static $defaults = array(
        
        'StyleBoxShadowAlpha' => 1,
        'StyleBoxShadowInset' => 0,
        
        'StyleTextShadowAlpha' => 1,
        
        'StyleBoxShadowOffsetXUnit' => 'px',
        'StyleBoxShadowOffsetYUnit' => 'px',
        'StyleBoxShadowBlurRadiusUnit' => 'px',
        'StyleBoxShadowSpreadRadiusUnit' => 'px',
        
        'StyleTextShadowOffsetXUnit' => 'px',
        'StyleTextShadowOffsetYUnit' => 'px',
        'StyleTextShadowBlurRadiusUnit' => 'px'
        
    );
    
    protected $css = array(
        'box-shadow' => 'getStyleBoxShadowCSS',
        'text-shadow' => 'getStyleTextShadowCSS'
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
                'StyleShadowToggle',
                _t('StyleShadowExtension.SHADOW', 'Shadow'),
                array(
                    FieldGroup::create(
                        _t('StyleShadowExtension.BOXSHADOW', 'Box shadow'),
                        array(
                            TextField::create('StyleBoxShadowOffsetX', '')->setAttribute(
                                'placeholder',
                                _t('StyleShadowExtension.OFFSETX', 'Offset X')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleBoxShadowOffsetXUnit',
                                '',
                                $this->owner->dbObject('StyleBoxShadowOffsetXUnit')->enumValues()
                            ),
                            TextField::create('StyleBoxShadowOffsetY', '')->setAttribute(
                                'placeholder',
                                _t('StyleShadowExtension.OFFSETY', 'Offset Y')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleBoxShadowOffsetYUnit',
                                '',
                                $this->owner->dbObject('StyleBoxShadowOffsetYUnit')->enumValues()
                            ),
                            TextField::create('StyleBoxShadowBlurRadius', '')->setAttribute(
                                'placeholder',
                                _t('StyleShadowExtension.BLUR', 'Blur')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleBoxShadowBlurRadiusUnit',
                                '',
                                $this->owner->dbObject('StyleBoxShadowBlurRadiusUnit')->enumValues()
                            ),
                            TextField::create('StyleBoxShadowSpreadRadius', '')->setAttribute(
                                'placeholder',
                                _t('StyleShadowExtension.SPREAD', 'Spread')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleBoxShadowSpreadRadiusUnit',
                                '',
                                $this->owner->dbObject('StyleBoxShadowSpreadRadiusUnit')->enumValues()
                            ),
                            CheckboxField::create(
                                'StyleBoxShadowInset',
                                _t('StyleShadowExtension.INSET', 'Inset')
                            )
                        )
                    ),
                    FieldGroup::create(
                        _t('StyleShadowExtension.BOXSHADOWCOLOR', 'Box shadow color'),
                        array(
                            ColorField::create('StyleBoxShadowColor', ''),
                            DropdownField::create('StyleBoxShadowAlpha', '', $alpha)
                        )
                    ),
                    FieldGroup::create(
                        _t('StyleShadowExtension.TEXTSHADOW', 'Text shadow'),
                        array(
                            TextField::create('StyleTextShadowOffsetX', '')->setAttribute(
                                'placeholder',
                                _t('StyleShadowExtension.OFFSETX', 'Offset X')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleTextShadowOffsetXUnit',
                                '',
                                $this->owner->dbObject('StyleTextShadowOffsetXUnit')->enumValues()
                            ),
                            TextField::create('StyleTextShadowOffsetY', '')->setAttribute(
                                'placeholder',
                                _t('StyleShadowExtension.OFFSETY', 'Offset Y')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleTextShadowOffsetYUnit',
                                '',
                                $this->owner->dbObject('StyleTextShadowOffsetYUnit')->enumValues()
                            ),
                            TextField::create('StyleTextShadowBlurRadius', '')->setAttribute(
                                'placeholder',
                                _t('StyleShadowExtension.BLUR', 'Blur')
                            )->setMaxLength(5),
                            DropdownField::create(
                                'StyleTextShadowBlurRadiusUnit',
                                '',
                                $this->owner->dbObject('StyleTextShadowBlurRadiusUnit')->enumValues()
                            )
                        )
                    ),
                    FieldGroup::create(
                        _t('StyleShadowExtension.TEXTSHADOWCOLOR', 'Text shadow color'),
                        array(
                            ColorField::create('StyleTextShadowColor', ''),
                            DropdownField::create('StyleTextShadowAlpha', '', $alpha)
                        )
                    )
                )
            )
        );
    }
    
    /**
     * Answers the CSS string for the box-shadow style.
     *
     * @return string
     */
    public function getStyleBoxShadowCSS()
    {
        $css = array();
        
        if ($this->owner->StyleBoxShadowOffsetX != '' && $this->owner->StyleBoxShadowOffsetY != '') {
            
            if ($this->owner->StyleBoxShadowInset) {
                $css[] = "inset";
            }
            
            $css[] = $this->owner->StyleBoxShadowOffsetX . $this->owner->StyleBoxShadowOffsetXUnit;
            $css[] = $this->owner->StyleBoxShadowOffsetY . $this->owner->StyleBoxShadowOffsetYUnit;
            
            if ($this->owner->StyleBoxShadowBlurRadius != '') {
                
                $css[] = $this->owner->StyleBoxShadowBlurRadius . $this->owner->StyleBoxShadowBlurRadiusUnit;
                
            }
            
            if ($this->owner->StyleBoxShadowSpreadRadius != '') {
                
                $css[] = $this->owner->StyleBoxShadowSpreadRadius . $this->owner->StyleBoxShadowSpreadRadiusUnit;
                
            }
            
            if ($this->owner->StyleBoxShadowColor != '') {
                
                $alpha = $this->owner->StyleBoxShadowAlpha;
                $color = $this->owner->dbObject('StyleBoxShadowColor');
                
                $css[] = $color->CSSColor($alpha);
                
            }
            
        }
        
        return implode(' ', $css);
    }
    
    /**
     * Answers the CSS string for the text-shadow style.
     *
     * @return string
     */
    public function getStyleTextShadowCSS()
    {
        $css = array();
        
        if ($this->owner->StyleTextShadowOffsetX != '' && $this->owner->StyleTextShadowOffsetY != '') {
            
            $css[] = $this->owner->StyleTextShadowOffsetX . $this->owner->StyleTextShadowOffsetXUnit;
            $css[] = $this->owner->StyleTextShadowOffsetY . $this->owner->StyleTextShadowOffsetYUnit;
            
            if ($this->owner->StyleTextShadowBlurRadius != '') {
                
                $css[] = $this->owner->StyleTextShadowBlurRadius . $this->owner->StyleTextShadowBlurRadiusUnit;
                
            }
            
            if ($this->owner->StyleTextShadowColor != '') {
                
                $alpha = $this->owner->StyleTextShadowAlpha;
                $color = $this->owner->dbObject('StyleTextShadowColor');
                
                $css[] = $color->CSSColor($alpha);
                
            }
            
        }
        
        return implode(' ', $css);
    }
}
