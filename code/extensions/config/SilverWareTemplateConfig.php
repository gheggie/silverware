<?php

/**
 * An extension of the SilverWare config extension class to add template settings to site config.
 */
class SilverWareTemplateConfig extends SilverWareConfigExtension
{
    private static $db = array(
        
        'SilverWareBodyColor' => 'Color',
        'SilverWareHeadingColor' => 'Color',
        'SilverWareMonospaceColor' => 'Color',
        
        'SilverWareLinkColor' => 'Color',
        'SilverWareLinkHoverColor' => 'Color',
        'SilverWareLinkActiveColor' => 'Color',
        'SilverWareLinkVisitedColor' => 'Color',
        
        'SilverWareButtonColor' => 'Color',
        'SilverWareButtonHoverColor' => 'Color',
        'SilverWareButtonActiveColor' => 'Color',
        
        'SilverWareButtonBackgroundColor' => 'Color',
        'SilverWareButtonHoverBackgroundColor' => 'Color',
        'SilverWareButtonActiveBackgroundColor' => 'Color',
        
        'SilverWareButton2Color' => 'Color',
        'SilverWareButton2HoverColor' => 'Color',
        'SilverWareButton2ActiveColor' => 'Color',
        
        'SilverWareButton2BackgroundColor' => 'Color',
        'SilverWareButton2HoverBackgroundColor' => 'Color',
        'SilverWareButton2ActiveBackgroundColor' => 'Color',
        
        'SilverWareHeading1Color' => 'Color',
        'SilverWareHeading2Color' => 'Color',
        'SilverWareHeading3Color' => 'Color',
        'SilverWareHeading4Color' => 'Color',
        'SilverWareHeading5Color' => 'Color',
        'SilverWareHeading6Color' => 'Color',
        
        'SilverWareHeading1Wide' => 'Varchar(16)',
        'SilverWareHeading2Wide' => 'Varchar(16)',
        'SilverWareHeading3Wide' => 'Varchar(16)',
        'SilverWareHeading4Wide' => 'Varchar(16)',
        'SilverWareHeading5Wide' => 'Varchar(16)',
        'SilverWareHeading6Wide' => 'Varchar(16)',
        
        'SilverWareHeading1Narrow' => 'Varchar(16)',
        'SilverWareHeading2Narrow' => 'Varchar(16)',
        'SilverWareHeading3Narrow' => 'Varchar(16)',
        'SilverWareHeading4Narrow' => 'Varchar(16)',
        'SilverWareHeading5Narrow' => 'Varchar(16)',
        'SilverWareHeading6Narrow' => 'Varchar(16)',
        
        'SilverWareHeading1Weight' => 'Varchar(16)',
        'SilverWareHeading2Weight' => 'Varchar(16)',
        'SilverWareHeading3Weight' => 'Varchar(16)',
        'SilverWareHeading4Weight' => 'Varchar(16)',
        'SilverWareHeading5Weight' => 'Varchar(16)',
        'SilverWareHeading6Weight' => 'Varchar(16)',
        
        'SilverWareHeading1LineHeight' => 'Varchar(16)',
        'SilverWareHeading2LineHeight' => 'Varchar(16)',
        'SilverWareHeading3LineHeight' => 'Varchar(16)',
        'SilverWareHeading4LineHeight' => 'Varchar(16)',
        'SilverWareHeading5LineHeight' => 'Varchar(16)',
        'SilverWareHeading6LineHeight' => 'Varchar(16)',
        
        'SilverWareButtonWide' => 'Varchar(16)',
        'SilverWareButtonNarrow' => 'Varchar(16)',
        'SilverWareButtonUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')",
        
        'SilverWareHeadingFactor' => 'Decimal(3,2,1)',
        'SilverWareHeadingUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')"
        
    );
    
    private static $has_one = array(
        'SilverWareBodyFont' => 'SilverWareFont',
        'SilverWareButtonFont' => 'SilverWareFont',
        'SilverWareHeadingFont' => 'SilverWareFont',
        'SilverWareMonospaceFont' => 'SilverWareFont'
    );
    
    private static $defaults = array(
        
        'SilverWareBodyColor' => '444444',
        'SilverWareHeadingColor' => '444444',
        'SilverWareMonospaceColor' => '444444',
        
        'SilverWareHeading1Wide' => '5.0',
        'SilverWareHeading2Wide' => '4.2',
        'SilverWareHeading3Wide' => '3.6',
        'SilverWareHeading4Wide' => '3.0',
        'SilverWareHeading5Wide' => '2.4',
        'SilverWareHeading6Wide' => '1.5',
        
        'SilverWareHeading1Narrow' => '4.0',
        'SilverWareHeading2Narrow' => '3.6',
        'SilverWareHeading3Narrow' => '3.0',
        'SilverWareHeading4Narrow' => '2.4',
        'SilverWareHeading5Narrow' => '1.8',
        'SilverWareHeading6Narrow' => '1.5',
        
        'SilverWareHeading1LineHeight' => '1.2',
        'SilverWareHeading2LineHeight' => '1.25',
        'SilverWareHeading3LineHeight' => '1.3',
        'SilverWareHeading4LineHeight' => '1.35',
        'SilverWareHeading5LineHeight' => '1.5',
        'SilverWareHeading6LineHeight' => '1.6',
        
        'SilverWareHeading1Weight' => '300',
        'SilverWareHeading2Weight' => '300',
        'SilverWareHeading3Weight' => '300',
        'SilverWareHeading4Weight' => '300',
        'SilverWareHeading5Weight' => '300',
        'SilverWareHeading6Weight' => '300',
        
        'SilverWareButtonWide' => '1.5',
        'SilverWareButtonNarrow' => '1.2',
        'SilverWareButtonUnit' => 'rem',
        
        'SilverWareHeadingFactor' => 1,
        'SilverWareHeadingUnit' => 'rem',
        
        'SilverWareLinkColor' => '139fda',
        
        'SilverWareButtonColor' => 'ffffff',
        'SilverWareButtonHoverColor' => 'ffffff',
        'SilverWareButtonActiveColor' => 'ffffff',
        
        'SilverWareButtonBackgroundColor' => '139fda',
        'SilverWareButtonHoverBackgroundColor' => '33bffa',
        'SilverWareButtonActiveBackgroundColor' => '038fca',
        
        'SilverWareButton2Color' => 'ffffff',
        'SilverWareButton2HoverColor' => 'ffffff',
        'SilverWareButton2ActiveColor' => 'ffffff',
        
        'SilverWareButton2BackgroundColor' => '888888',
        'SilverWareButton2HoverBackgroundColor' => 'aaaaaa',
        'SilverWareButton2ActiveBackgroundColor' => '777777'
        
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects (from parent):
        
        parent::updateCMSFields($fields);
        
        // Create Template Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.Template', _t('SilverWareTemplateConfig.TEMPLATE', 'Template'));
        
        // Obtain Factor Options:
        
        $factor = SilverWareTools::percentage_options(50, 200, 10);
        
        // Add Fields to Template Tab:
        
        $fields->addFieldsToTab(
            'Root.SilverWare.Template',
            array(
                ToggleCompositeField::create(
                    'TemplateColorsToggle',
                    _t('SilverWareTemplateConfig.COLORS', 'Colors'),
                    array(
                        ColorField::create(
                            'SilverWareBodyColor',
                            _t('SilverWareTemplateConfig.BODYCOLOR', 'Body color')
                        ),
                        ColorField::create(
                            'SilverWareHeadingColor',
                            _t('SilverWareTemplateConfig.HEADINGCOLOR', 'Heading color')
                        ),
                        ColorField::create(
                            'SilverWareMonospaceColor',
                            _t('SilverWareTemplateConfig.MONOSPACECOLOR', 'Monospace color')
                        ),
                        FieldGroup::create(
                            _t('SilverWareTemplateConfig.LINKCOLORS', 'Link colors'),
                            array(
                                ColorField::create(
                                    'SilverWareLinkColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.LINKS', 'Links')
                                ),
                                ColorField::create(
                                    'SilverWareLinkHoverColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.HOVER', 'Hover')
                                ),
                                ColorField::create(
                                    'SilverWareLinkActiveColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.ACTIVE', 'Active')
                                ),
                                ColorField::create(
                                    'SilverWareLinkVisitedColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.VISITED', 'Visited')
                                )
                            )
                        ),
                        FieldGroup::create(
                            _t('SilverWareTemplateConfig.BUTTONCOLORS', 'Button colors'),
                            array(
                                ColorField::create(
                                    'SilverWareButtonColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.BUTTON', 'Button')
                                ),
                                ColorField::create(
                                    'SilverWareButtonHoverColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.HOVER', 'Hover')
                                ),
                                ColorField::create(
                                    'SilverWareButtonActiveColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.ACTIVE', 'Active')
                                )
                            )
                        ),
                        FieldGroup::create(
                            _t('SilverWareTemplateConfig.BUTTONCOLORS', 'Button background colors'),
                            array(
                                ColorField::create(
                                    'SilverWareButtonBackgroundColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.BUTTON', 'Button')
                                ),
                                ColorField::create(
                                    'SilverWareButtonHoverBackgroundColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.HOVER', 'Hover')
                                ),
                                ColorField::create(
                                    'SilverWareButtonActiveBackgroundColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.ACTIVE', 'Active')
                                )
                            )
                        ),
                        FieldGroup::create(
                            _t('SilverWareTemplateConfig.SECONDARYBUTTONCOLORS', 'Secondary button colors'),
                            array(
                                ColorField::create(
                                    'SilverWareButton2Color',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.BUTTON', 'Button')
                                ),
                                ColorField::create(
                                    'SilverWareButton2HoverColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.HOVER', 'Hover')
                                ),
                                ColorField::create(
                                    'SilverWareButton2ActiveColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.ACTIVE', 'Active')
                                )
                            )
                        ),
                        FieldGroup::create(
                            _t(
                                'SilverWareTemplateConfig.SECONDARYBUTTONBACKGROUNDCOLORS',
                                'Secondary button background colors'
                            ),
                            array(
                                ColorField::create(
                                    'SilverWareButton2BackgroundColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.BUTTON', 'Button')
                                ),
                                ColorField::create(
                                    'SilverWareButton2HoverBackgroundColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.HOVER', 'Hover')
                                ),
                                ColorField::create(
                                    'SilverWareButton2ActiveBackgroundColor',
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.ACTIVE', 'Active')
                                )
                            )
                        )
                    )
                ),
                ToggleCompositeField::create(
                    'TemplateFontsToggle',
                    _t('SilverWareTemplateConfig.FONTS', 'Fonts'),
                    array(
                        DropdownField::create(
                            'SilverWareBodyFontID',
                            _t('SilverWareTemplateConfig.BODYFONT', 'Body font'),
                            SilverWareFont::get()->map()
                        )->setEmptyString(' '),
                        DropdownField::create(
                            'SilverWareHeadingFontID',
                            _t('SilverWareTemplateConfig.HEADINGFONT', 'Heading font'),
                            SilverWareFont::get()->map()
                        )->setEmptyString(' '),
                        DropdownField::create(
                            'SilverWareMonospaceFontID',
                            _t('SilverWareTemplateConfig.MONOSPACEFONT', 'Monospace font'),
                            SilverWareFont::get()->map()
                        )->setEmptyString(' '),
                        DropdownField::create(
                            'SilverWareButtonFontID',
                            _t('SilverWareTemplateConfig.MONOSPACEFONT', 'Button font'),
                            SilverWareFont::get()->map()
                        )->setEmptyString(' ')
                    )
                ),
                ToggleCompositeField::create(
                    'TemplateButtonsToggle',
                    _t('SilverWareTemplateConfig.BUTTONS', 'Buttons'),
                    array(
                        FieldGroup::create(
                            _t('SilverWareTemplateConfig.BUTTONFONTSIZE', 'Button font size'),
                            array(
                                TextField::create(
                                    "SilverWareButtonNarrow",
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.NARROWSIZE', 'Narrow Size')
                                ),
                                TextField::create(
                                    "SilverWareButtonWide",
                                    ''
                                )->setAttribute(
                                    'placeholder',
                                    _t('SilverWareTemplateConfig.WIDESIZE', 'Wide Size')
                                ),
                                DropdownField::create(
                                    'SilverWareButtonUnit',
                                    '',
                                    $this->owner->dbObject('SilverWareButtonUnit')->enumValues()
                                )
                            )
                        )
                    )
                ),
                ToggleCompositeField::create(
                    'TemplateHeadingsToggle',
                    _t('SilverWareTemplateConfig.HEADINGS', 'Headings'),
                    array(
                        $this->owner->getSilverWareHeadingFieldGroup(1),
                        $this->owner->getSilverWareHeadingFieldGroup(2),
                        $this->owner->getSilverWareHeadingFieldGroup(3),
                        $this->owner->getSilverWareHeadingFieldGroup(4),
                        $this->owner->getSilverWareHeadingFieldGroup(5),
                        $this->owner->getSilverWareHeadingFieldGroup(6),
                        DropdownField::create(
                            'SilverWareHeadingUnit',
                            _t('SilverWareTemplateConfig.HEADINGUNIT', 'Heading unit'),
                            $this->owner->dbObject('SilverWareHeadingUnit')->enumValues()
                        ),
                        DropdownField::create(
                            'SilverWareHeadingFactor',
                            _t('SilverWareTemplateConfig.HEADINGFACTOR', 'Heading factor'),
                            $factor
                        )
                    )
                )
            )
        );
    }
    
    /**
     * Answers the CSS string for the narrow button font-size style.
     *
     * @return string
     */
    public function getSilverWareButtonNarrowCSS()
    {
        return $this->getSilverWareFontSizeCSS('SilverWareButtonNarrow', 'SilverWareButtonUnit');
    }
    
    /**
     * Answers the CSS string for the wide button font-size style.
     *
     * @return string
     */
    public function getSilverWareButtonWideCSS()
    {
        return $this->getSilverWareFontSizeCSS('SilverWareButtonWide', 'SilverWareButtonUnit');
    }
    
    /**
     * Answers the CSS string for the narrow H1 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading1NarrowCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading1Narrow');
    }
    
    /**
     * Answers the CSS string for the narrow H2 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading2NarrowCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading2Narrow');
    }
    
    /**
     * Answers the CSS string for the narrow H3 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading3NarrowCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading3Narrow');
    }
    
    /**
     * Answers the CSS string for the narrow H4 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading4NarrowCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading4Narrow');
    }
    
    /**
     * Answers the CSS string for the narrow H5 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading5NarrowCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading5Narrow');
    }
    
    /**
     * Answers the CSS string for the narrow H6 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading6NarrowCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading6Narrow');
    }
    
    /**
     * Answers the CSS string for the wide H1 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading1WideCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading1Wide');
    }
    
    /**
     * Answers the CSS string for the wide H2 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading2WideCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading2Wide');
    }
    
    /**
     * Answers the CSS string for the wide H3 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading3WideCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading3Wide');
    }
    
    /**
     * Answers the CSS string for the wide H4 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading4WideCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading4Wide');
    }
    
    /**
     * Answers the CSS string for the wide H5 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading5WideCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading5Wide');
    }
    
    /**
     * Answers the CSS string for the wide H6 font-size style.
     *
     * @return string
     */
    public function getSilverWareHeading6WideCSS()
    {
        return $this->getSilverWareHeadingFontSizeCSS('SilverWareHeading6Wide');
    }
    
    /**
     * Answers the CSS string for the specified font-size attribute.
     *
     * @param string $name
     * @return string
     */
    public function getSilverWareFontSizeCSS($name, $unit)
    {
        if ($this->owner->$name) {
            return (float) $this->owner->$name . $this->owner->$unit;
        }
    }
    
    /**
     * Answers the CSS string for the specified font-size attribute.
     *
     * @param string $name
     * @return string
     */
    public function getSilverWareHeadingFontSizeCSS($name)
    {
        $css = array();
        
        if ($this->owner->$name) {
            
            $size = (float) $this->owner->$name;
            
            if ($this->owner->SilverWareHeadingFactor != '') {
                $size = round($size * (float) $this->owner->SilverWareHeadingFactor, 2);
            }
            
            $css[] = $size . $this->owner->SilverWareHeadingUnit;
            
        }
        
        return implode(' ', $css);
    }
    
    /**
     * Answers a field group for the heading with the given number.
     *
     * @param integer $number
     * @return FieldGroup
     */
    public function getSilverWareHeadingFieldGroup($number)
    {
        return FieldGroup::create(
            _t("SilverWareTemplateConfig.HEADING{$number}", "Heading {$number}"),
            array(
                TextField::create(
                    "SilverWareHeading{$number}Narrow",
                    ''
                )->setAttribute(
                    'placeholder',
                    _t('SilverWareTemplateConfig.NARROWSIZE', 'Narrow Size')
                ),
                TextField::create(
                    "SilverWareHeading{$number}Wide",
                    ''
                )->setAttribute(
                    'placeholder',
                    _t('SilverWareTemplateConfig.WIDESIZE', 'Wide Size')
                ),
                TextField::create(
                    "SilverWareHeading{$number}LineHeight",
                    ''
                )->setAttribute(
                    'placeholder',
                    _t('SilverWareTemplateConfig.LINEHEIGHT', 'Line Height')
                ),
                DropdownField::create(
                    "SilverWareHeading{$number}Weight",
                    '',
                    SilverWareFont::get_weights_with_number(true)
                )->setEmptyString(_t('SilverWareTemplateConfig.WEIGHT', 'Weight')),
                ColorField::create(
                    "SilverWareHeading{$number}Color",
                    ''
                )->setAttribute(
                    'placeholder',
                    _t('SilverWareTemplateConfig.COLOR', 'Color')
                )
            )
        );
    }
}
