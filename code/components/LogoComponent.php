<?php

/**
 * An extension of the base component class for a logo component.
 */
class LogoComponent extends BaseComponent
{
    private static $singular_name = "Logo Component";
    private static $plural_name   = "Logo Components";
    
    private static $description = "A component to show a logo with optional text";
    
    private static $icon = "silverware/images/icons/components/LogoComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $asset_folder = "Logos";
    
    private static $db = array(
        'HideLogo' => 'Boolean',
        'HideIcon' => 'Boolean',
        'HideText' => 'Boolean',
        'LogoTitle' => 'Varchar(255)',
        'LogoSubtitle' => 'Varchar(255)',
        'LinkDisabled' => 'Boolean',
        'LogoTitleTag' => "Enum('h1, h2, h3, h4, h5, h6', 'h1')",
        'LogoSubtitleTag' => "Enum('h1, h2, h3, h4, h5, h6', 'h2')",
        'LogoWidthWide' => 'Varchar(16)',
        'LogoHeightWide' => 'Varchar(16)',
        'LogoWidthNarrow' => 'Varchar(16)',
        'LogoHeightNarrow' => 'Varchar(16)',
        'UseRetinaBitmap' => 'Boolean',
        'IconSize' => 'Int',
        'IconUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'TitleFontSize' => 'Decimal(3,2,1)',
        'TitleFontUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')",
        'TitleFontWeight' => 'Varchar(16)',
        'TitleFontColor' => 'Color',
        'TitleFontAlpha' => 'Decimal(3,2,1)',
        'SubtitleFontSize' => 'Decimal(3,2,1)',
        'SubtitleFontUnit' => "Enum('px, em, rem, pt, cm, in', 'rem')",
        'SubtitleFontWeight' => 'Varchar(16)',
        'SubtitleFontColor' => 'Color',
        'SubtitleFontAlpha' => 'Decimal(3,2,1)',
        'IconSizeNarrow' => 'Decimal(3,2,0.75)',
        'TextSizeNarrow' => 'Decimal(3,2,0.75)',
        'LogoMarginTop' => 'Varchar(16)',
        'LogoMarginLeft' => 'Varchar(16)',
        'LogoMarginRight' => 'Varchar(16)',
        'LogoMarginBottom' => 'Varchar(16)',
        'IconMarginTop' => 'Varchar(16)',
        'IconMarginLeft' => 'Varchar(16)',
        'IconMarginRight' => 'Varchar(16)',
        'IconMarginBottom' => 'Varchar(16)',
        'TextMarginTop' => 'Varchar(16)',
        'TextMarginLeft' => 'Varchar(16)',
        'TextMarginRight' => 'Varchar(16)',
        'TextMarginBottom' => 'Varchar(16)',
        'LayoutWide' => "Enum('Rows, Columns', 'Columns')"
    );
    
    private static $has_one = array(
        'LinkPage' => 'SiteTree',
        'LogoBitmap' => 'Image',
        'LogoVector' => 'File',
        'TitleFont' => 'SilverWareFont',
        'SubtitleFont' => 'SilverWareFont'
    );
    
    private static $defaults = array(
        'HideLogo' => 0,
        'HideIcon' => 0,
        'HideText' => 0,
        'HideTitle' => 1,
        'LinkDisabled' => 0,
        'LogoTitleTag' => 'h1',
        'LogoSubtitleTag' => 'h2',
        'UseRetinaBitmap' => 1,
        'StyleAlignmentWide' => 'Left',
        'StyleAlignmentNarrow' => 'Center',
        'IconSize' => 64,
        'IconUnit' => 'px',
        'TitleFontSize' => 3.2,
        'TitleFontUnit' => 'rem',
        'SubtitleFontSize' => 2.4,
        'SubtitleFontUnit' => 'rem',
        'TitleFontWeight' => 700,
        'SubtitleFontWeight' => 400,
        'IconSizeNarrow' => 0.75,
        'TextSizeNarrow' => 0.75,
        'LayoutWide' => 'Columns',
        'TitleFontAlpha' => 1,
        'SubtitleFontAlpha' => 1
    );
    
    private static $extensions = array(
        'StyleAlignmentExtension',
        'SilverWareFontIconExtension'
    );
    
    private static $required_themed_css = array(
        'logo-component'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create(
                    'LogoTitle',
                    _t('LogoComponent.LOGOTITLE', 'Logo title')
                ),
                TextField::create(
                    'LogoSubtitle',
                    _t('LogoComponent.LOGOSUBTITLE', 'Logo subtitle')
                ),
                TreeDropdownField::create(
                    'LinkPageID',
                    _t('LogoComponent.LINKPAGE', 'Link page'),
                    'SiteTree'
                )
            )
        );
        
        // Insert Logo Tab:
        
        $fields->insertAfter(
            Tab::create(
                'Logo',
                _t('LogoComponent.LOGO', 'Logo')
            ),
            'Main'
        );
        
        // Create Logo Fields:
        
        $fields->addFieldsToTab(
            'Root.Logo',
            array(
                $vector = UploadField::create(
                    'LogoVector',
                    _t('LogoComponent.LOGOVECTOR', 'Vector logo')
                )->setRightTitle(
                    _t(
                        'LogoComponent.LOGOVECTORRIGHTTITLE',
                        'Scalable Vector Graphics (SVG) format accepted (ensure SVG is permitted by assets .htaccess).'
                    )
                ),
                $bitmap = UploadField::create(
                    'LogoBitmap',
                    _t('LogoComponent.LOGOBITMAP', 'Bitmap logo')
                )->setRightTitle(
                    _t(
                        'LogoComponent.LOGOBITMAPRIGHTTITLE',
                        'Portable Network Graphics (PNG) format accepted (high resolution + transparency recommended).'
                    )
                )
            )
        );
        
        // Define Upload Fields:
        
        $vector->setFolderName($this->config()->asset_folder);
        $vector->setAllowedExtensions(array('svg'));
        
        $bitmap->setFolderName($this->config()->asset_folder);
        $bitmap->setAllowedExtensions(array('png'));
        
        // Modify Icon Fields:
        
        $fields->removeByName('FontIconSize');
        
        // Create Icon Fields:
        
        $fields->insertAfter(
            'FontIcon',
            FieldGroup::create(
                _t('LogoComponent.ICONSIZE', 'Icon size'),
                array(
                    NumericField::create('IconSize', '')->setAttribute(
                        'placeholder',
                        _t('LogoComponent.SIZE', 'Size')
                    )->setMaxLength(5),
                    DropdownField::create(
                        'IconUnit',
                        '',
                        $this->dbObject('IconUnit')->enumValues()
                    )
                )
            )
        );
        
        // Obtain Alpha Options:
        
        $alpha = SilverWareTools::percentage_options(100, 0, 5);
        
        // Create Style Fields:
        
        $fields->addFieldsToTab(
            'Root.Style',
            array(
                ToggleCompositeField::create(
                    'LogoComponentStyle',
                    $this->i18n_singular_name(),
                    array(
                        DropdownField::create(
                            'LayoutWide',
                            _t('LogoComponent.LAYOUTWIDE', 'Layout (wide)'),
                            $this->dbObject('LayoutWide')->enumValues()
                        ),
                        FieldGroup::create(
                            _t('LogoComponent.LOGODIMENSIONSWIDE', 'Logo dimensions (wide)'),
                            array(
                                TextField::create('LogoWidthWide', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.WIDTH', 'Width')
                                ),
                                LiteralField::create('ImageBy', '<i class="fa fa-times by"></i>'),
                                TextField::create('LogoHeightWide', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.HEIGHT', 'Height')
                                )
                            )
                        ),
                        FieldGroup::create(
                            _t('LogoComponent.LOGODIMENSIONSNARROW', 'Logo dimensions (narrow)'),
                            array(
                                TextField::create('LogoWidthNarrow', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.WIDTH', 'Width')
                                ),
                                LiteralField::create('ImageBy', '<i class="fa fa-times by"></i>'),
                                TextField::create('LogoHeightNarrow', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.HEIGHT', 'Height')
                                )
                            )
                        ),
                        DropdownField::create(
                            'TitleFontID',
                            _t('LogoComponent.TITLEFONT', 'Title font'),
                            SilverWareFont::get()->map()
                        )->setEmptyString(' '),
                        FieldGroup::create(
                            _t('LogoComponent.TITLEFONTSIZE', 'Title font size'),
                            array(
                                NumericField::create('TitleFontSize', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.SIZE', 'Size')
                                )->setMaxLength(5),
                                DropdownField::create(
                                    'TitleFontUnit',
                                    '',
                                    $this->dbObject('TitleFontUnit')->enumValues()
                                )
                            )
                        ),
                        DropdownField::create(
                            'TitleFontWeight',
                            _t('LogoComponent.TITLEFONTWEIGHT', 'Title font weight'),
                            SilverWareFont::get_weights_with_number()
                        )->setEmptyString(' '),
                        FieldGroup::create(
                            _t('LogoComponent.TITLEFONTCOLOR', 'Title font color'),
                            array(
                                ColorField::create('TitleFontColor', ''),
                                DropdownField::create('TitleFontAlpha', '', $alpha)
                            )
                        ),
                        DropdownField::create(
                            'SubtitleFontID',
                            _t('LogoComponent.SUBTITLEFONT', 'Subtitle font'),
                            SilverWareFont::get()->map()
                        )->setEmptyString(' '),
                        FieldGroup::create(
                            _t('LogoComponent.SUBTITLEFONTSIZE', 'Subtitle font size'),
                            array(
                                NumericField::create('SubtitleFontSize', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.SIZE', 'Size')
                                )->setMaxLength(5),
                                DropdownField::create(
                                    'SubtitleFontUnit',
                                    '',
                                    $this->dbObject('SubtitleFontUnit')->enumValues()
                                )
                            )
                        ),
                        DropdownField::create(
                            'SubtitleFontWeight',
                            _t('LogoComponent.SUBTITLEFONTWEIGHT', 'Subtitle font weight'),
                            SilverWareFont::get_weights_with_number()
                        )->setEmptyString(' '),
                        FieldGroup::create(
                            _t('LogoComponent.SUBTITLEFONTCOLOR', 'Subtitle font color'),
                            array(
                                ColorField::create('SubtitleFontColor', ''),
                                DropdownField::create('SubtitleFontAlpha', '', $alpha)
                            )
                        ),
                        DropdownField::create(
                            'IconSizeNarrow',
                            _t('LogoComponent.ICONSIZENARROW', 'Icon size (narrow)'),
                            SilverWareTools::percentage_options(100, 50, 5)
                        )->setRightTitle(
                            _t(
                                'LogoComponent.ICONSIZENARROWRIGHTTITLE',
                                'Reduces the icon size by the specified factor for narrow devices.'
                            )
                        ),
                        DropdownField::create(
                            'TextSizeNarrow',
                            _t('LogoComponent.TEXTSIZENARROW', 'Text size (narrow)'),
                            SilverWareTools::percentage_options(100, 50, 5)
                        )->setRightTitle(
                            _t(
                                'LogoComponent.TEXTSIZENARROWRIGHTTITLE',
                                'Reduces the text size by the specified factor for narrow devices.'
                            )
                        ),
                        FieldGroup::create(
                            _t('LogoComponent.LOGOMARGIN', 'Logo margin (in pixels)'),
                            array(
                                TextField::create('LogoMarginTop', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.TOP', 'Top')
                                )->setMaxLength(5),
                                TextField::create('LogoMarginLeft', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.LEFT', 'Left')
                                )->setMaxLength(5),
                                TextField::create('LogoMarginRight', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.RIGHT', 'Right')
                                )->setMaxLength(5),
                                TextField::create('LogoMarginBottom', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.BOTTOM', 'Bottom')
                                )->setMaxLength(5)
                            )
                        ),
                        FieldGroup::create(
                            _t('LogoComponent.ICONMARGIN', 'Icon margin (in pixels)'),
                            array(
                                TextField::create('IconMarginTop', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.TOP', 'Top')
                                )->setMaxLength(5),
                                TextField::create('IconMarginLeft', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.LEFT', 'Left')
                                )->setMaxLength(5),
                                TextField::create('IconMarginRight', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.RIGHT', 'Right')
                                )->setMaxLength(5),
                                TextField::create('IconMarginBottom', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.BOTTOM', 'Bottom')
                                )->setMaxLength(5)
                            )
                        ),
                        FieldGroup::create(
                            _t('LogoComponent.ICONMARGIN', 'Text margin (in pixels)'),
                            array(
                                TextField::create('TextMarginTop', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.TOP', 'Top')
                                )->setMaxLength(5),
                                TextField::create('TextMarginLeft', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.LEFT', 'Left')
                                )->setMaxLength(5),
                                TextField::create('TextMarginRight', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.RIGHT', 'Right')
                                )->setMaxLength(5),
                                TextField::create('TextMarginBottom', '')->setAttribute(
                                    'placeholder',
                                    _t('LogoComponent.BOTTOM', 'Bottom')
                                )->setMaxLength(5)
                            )
                        )
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'LogoComponentOptions',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'LogoTitleTag',
                        _t('LogoComponent.LOGOTITLETAG', 'Logo title tag'),
                        $this->dbObject('LogoTitleTag')->enumValues()
                    ),
                    DropdownField::create(
                        'LogoSubtitleTag',
                        _t('LogoComponent.LOGOSUBTITLETAG', 'Logo subtitle tag'),
                        $this->dbObject('LogoSubtitleTag')->enumValues()
                    ),
                    CheckboxField::create(
                        'HideLogo',
                        _t('LogoComponent.HIDELOGO', 'Hide logo')
                    ),
                    CheckboxField::create(
                        'HideIcon',
                        _t('LogoComponent.HIDEICON', 'Hide icon')
                    ),
                    CheckboxField::create(
                        'HideText',
                        _t('LogoComponent.HIDETEXT', 'Hide text')
                    ),
                    CheckboxField::create(
                        'LinkDisabled',
                        _t('LogoComponent.LINKDISABLED', 'Link disabled')
                    ),
                    CheckboxField::create(
                        'UseRetinaBitmap',
                        _t('LogoComponent.USERETINABITMAP', 'Use HD bitmap for compatible devices (i.e. Retina)')
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Call Parent Event:
        
        parent::onBeforeWrite();
        
        // Fix Image Dimensions:
        
        $this->LogoWidthWide = SilverWareTools::integer_or_null($this->LogoWidthWide);
        $this->LogoHeightWide = SilverWareTools::integer_or_null($this->LogoHeightWide);
        
        $this->LogoWidthNarrow = SilverWareTools::integer_or_null($this->LogoWidthNarrow);
        $this->LogoHeightNarrow = SilverWareTools::integer_or_null($this->LogoHeightNarrow);
    }
    
    /**
     * Answers a string of class names for the logo wrapper element.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return implode(' ', $this->getWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the logo wrapper element.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        $classes = array('wrapper');
        
        $classes[] = strtolower('wide-' . $this->LayoutWide);
        
        $classes[] = strtolower($this->getStyleAlignmentWideClass());
        
        $classes[] = strtolower($this->getStyleAlignmentNarrowClass());
        
        $classes[] = strtolower($this->getStyleAlignmentVerticalClass());
        
        return $classes;
    }
    
    /**
     * Answers a string of class names for the vector image.
     *
     * @return string
     */
    public function getVectorClass()
    {
        return implode(' ', $this->getVectorClassNames());
    }
    
    /**
     * Answers an array of class names for the vector image.
     *
     * @return array
     */
    public function getVectorClassNames()
    {
        $classes = array('svg');
        
        if (!$this->HasBitmapLogo()) {
            $classes[] = "no-png";
        }
        
        return $classes;
    }
    
    /**
     * Answers a string of class names for the bitmap image.
     *
     * @return string
     */
    public function getBitmapClass()
    {
        return implode(' ', $this->getBitmapClassNames());
    }
    
    /**
     * Answers an array of class names for the bitmap image.
     *
     * @return array
     */
    public function getBitmapClassNames()
    {
        $classes = array('png');
        
        if (!$this->HasVectorLogo()) {
            $classes[] = "no-svg";
        }
        
        return $classes;
    }
    
    /**
     * Answers a string of class names for the retina image.
     *
     * @return string
     */
    public function getRetinaClass()
    {
        return implode(' ', $this->getRetinaClassNames());
    }
    
    /**
     * Answers an array of class names for the retina image.
     *
     * @return array
     */
    public function getRetinaClassNames()
    {
        $classes = array('png-hd');
        
        if (!$this->HasVectorLogo()) {
            $classes[] = "no-svg";
        }
        
        return $classes;
    }
    
    /**
     * Answers the CSS string for the icon font size style.
     *
     * @return string
     */
    public function getIconSizeCSS()
    {
        return $this->IconSize . $this->IconUnit;
    }
    
     /**
     * Answers the CSS string for the narrow icon font size style.
     *
     * @return string
     */
    public function getIconSizeNarrowCSS()
    {
        return round(($this->IconSize * $this->IconSizeNarrow), 2) . $this->IconUnit;
    }
    
    /**
     * Answers the CSS string for the title font size style.
     *
     * @return string
     */
    public function getTitleFontSizeCSS()
    {
        return $this->TitleFontSize . $this->TitleFontUnit;
    }
    
    /**
     * Answers the CSS string for the narrow title font size style.
     *
     * @return string
     */
    public function getTitleFontSizeNarrowCSS()
    {
        return round(($this->TitleFontSize * $this->TextSizeNarrow), 2) . $this->TitleFontUnit;
    }
    
    /**
     * Answers the CSS string for the title font family style.
     *
     * @return string
     */
    public function getTitleFontFamilyCSS()
    {
        if ($this->TitleFontID) {
            
            return $this->TitleFont()->getStyleFontFamily();
            
        }
    }
    
    /**
     * Answers the CSS string for the subtitle font size style.
     *
     * @return string
     */
    public function getSubtitleFontSizeCSS()
    {
        return $this->SubtitleFontSize . $this->SubtitleFontUnit;
    }
    
    /**
     * Answers the CSS string for the narrow subtitle font size style.
     *
     * @return string
     */
    public function getSubtitleFontSizeNarrowCSS()
    {
        return round(($this->SubtitleFontSize * $this->TextSizeNarrow), 2) . $this->SubtitleFontUnit;
    }
    
    /**
     * Answers the CSS string for the subtitle font family style.
     *
     * @return string
     */
    public function getSubtitleFontFamilyCSS()
    {
        if ($this->SubtitleFontID) {
            
            return $this->SubtitleFont()->getStyleFontFamily();
            
        }
    }
    
    /**
     * Answers the CSS string for the title font color style.
     *
     * @return string
     */
    public function getTitleFontColorCSS()
    {
        return $this->getCSSColor('TitleFontColor', 'TitleFontAlpha');
    }
    
    /**
     * Answers the CSS string for the subtitle font color style.
     *
     * @return string
     */
    public function getSubtitleFontColorCSS()
    {
        return $this->getCSSColor('SubtitleFontColor', 'SubtitleFontAlpha');
    }
    
    /**
     * Answers the CSS string for the logo margin-top style.
     *
     * @return string
     */
    public function getLogoMarginTopCSS()
    {
        if ($this->owner->LogoMarginTop != '') {
            return $this->owner->LogoMarginTop . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the logo margin-left style.
     *
     * @return string
     */
    public function getLogoMarginLeftCSS()
    {
        if ($this->owner->LogoMarginLeft != '') {
            return $this->owner->LogoMarginLeft . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the logo margin-right style.
     *
     * @return string
     */
    public function getLogoMarginRightCSS()
    {
        if ($this->owner->LogoMarginRight != '') {
            return $this->owner->LogoMarginRight . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the logo margin-bottom style.
     *
     * @return string
     */
    public function getLogoMarginBottomCSS()
    {
        if ($this->owner->LogoMarginBottom != '') {
            return $this->owner->LogoMarginBottom . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the icon margin-top style.
     *
     * @return string
     */
    public function getIconMarginTopCSS()
    {
        if ($this->owner->IconMarginTop != '') {
            return $this->owner->IconMarginTop . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the icon margin-left style.
     *
     * @return string
     */
    public function getIconMarginLeftCSS()
    {
        if ($this->owner->IconMarginLeft != '') {
            return $this->owner->IconMarginLeft . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the icon margin-right style.
     *
     * @return string
     */
    public function getIconMarginRightCSS()
    {
        if ($this->owner->IconMarginRight != '') {
            return $this->owner->IconMarginRight . 'px';
        }
    }

    /**
     * Answers the CSS string for the icon margin-bottom style.
     *
     * @return string
     */
    public function getIconMarginBottomCSS()
    {
        if ($this->owner->IconMarginBottom != '') {
            return $this->owner->IconMarginBottom . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the text margin-top style.
     *
     * @return string
     */
    public function getTextMarginTopCSS()
    {
        if ($this->owner->TextMarginTop != '') {
            return $this->owner->TextMarginTop . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the text margin-left style.
     *
     * @return string
     */
    public function getTextMarginLeftCSS()
    {
        if ($this->owner->TextMarginLeft != '') {
            return $this->owner->TextMarginLeft . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the text margin-right style.
     *
     * @return string
     */
    public function getTextMarginRightCSS()
    {
        if ($this->owner->TextMarginRight != '') {
            return $this->owner->TextMarginRight . 'px';
        }
    }
    
    /**
     * Answers the CSS string for the text margin-bottom style.
     *
     * @return string
     */
    public function getTextMarginBottomCSS()
    {
        if ($this->owner->TextMarginBottom != '') {
            return $this->owner->TextMarginBottom . 'px';
        }
    }
    
    /**
     * Answers true if the receiver has a logo defined.
     *
     * @return boolean
     */
    public function HasLogo()
    {
        return ($this->HasVectorLogo() || $this->HasBitmapLogo());
    }
    
    /**
     * Answers true if the logo is to be shown.
     *
     * @return boolean
     */
    public function LogoShown()
    {
        return ($this->HasLogo() && !$this->HideLogo);
    }
    
    /**
     * Answers true if the receiver has an icon defined.
     *
     * @return boolean
     */
    public function HasIcon()
    {
        return $this->HasFontIcon();
    }
    
    /**
     * Answers true if the icon is to be shown.
     *
     * @return boolean
     */
    public function IconShown()
    {
        return ($this->HasIcon() && !$this->HideIcon);
    }
    
    /**
     * Answers true if either the logo title or subtitle are defined.
     *
     * @return boolean
     */
    public function HasText()
    {
        return ($this->LogoTitle || $this->LogoSubtitle);
    }

    /**
     * Answers true if the logo title and subtitle are to be shown.
     *
     * @return boolean
     */
    public function TextShown()
    {
        return ($this->HasText() && !$this->HideText);
    }
    
    /**
     * Answers the page link for the receiver.
     *
     * @return string
     */
    public function getPageLink()
    {
        if ($this->LinkPageID) {
            return $this->LinkPage()->Link();
        }
    }
    
    /**
     * Answers true if the receiver has a page link.
     *
     * @return boolean
     */
    public function HasPageLink()
    {
        return (boolean) $this->getPageLink();
    }
    
    /**
     * Answers true if the receiver has a vector logo.
     *
     * @return boolean
     */
    public function HasVectorLogo()
    {
        return $this->LogoVector()->exists();
    }
    
    /**
     * Answers true if the receiver has a bitmap logo.
     *
     * @return boolean
     */
    public function HasBitmapLogo()
    {
        return $this->LogoBitmap()->exists();
    }
    
    /**
     * Answers true if the page link is enabled.
     *
     * @return boolean
     */
    public function PageLinkEnabled()
    {
        return ($this->HasPageLink() && !$this->LinkDisabled);
    }
    
    /**
     * Answers a resized version of the bitmap logo.
     *
     * @return Image
     */
    public function LogoBitmapResized()
    {
        if ($this->HasBitmapLogo()) {
            
            return ImageTools::resize_image(
                $this->LogoBitmap(),
                $this->LogoWidthWide,
                $this->LogoHeightWide,
                'fit'
            );
            
        }
    }
    
    /**
     * Answers a retina version of the bitmap logo.
     *
     * @return Image
     */
    public function LogoBitmapRetina()
    {
        if ($this->HasBitmapLogo()) {
            
            return ImageTools::resize_image(
                $this->LogoBitmap(),
                ($this->LogoWidthWide * 2),
                ($this->LogoHeightWide * 2),
                'fit'
            );
            
        }
    }
    
    /**
     * Answers the CSS color string for the specified color and alpha attributes.
     *
     * @param string $color Name of color attribute.
     * @param string $alpha Name of alpha attribute.
     * @return string
     */
    protected function getCSSColor($color, $alpha)
    {
        if ($this->$color != '') {
            
            return $this->dbObject($color)->CSSColor($this->$alpha);
            
        }
    }
}

/**
 * An extension of the base component controller class for a logo component.
 */
class LogoComponent_Controller extends BaseComponent_Controller
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
