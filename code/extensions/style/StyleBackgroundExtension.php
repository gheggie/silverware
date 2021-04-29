<?php

/**
 * An extension of the SilverWare style extension class to apply background styles to the extended object.
 */
class StyleBackgroundExtension extends SilverWareStyleExtension
{
    private static $db = array(
        'StyleBackgroundColor' => 'Color',
        'StyleBackgroundAlpha' => 'Decimal(3,2,1)',
        'StyleBackgroundParallax' => 'Boolean',
        'StyleBackgroundParallaxRatio' => 'Float',
        'StyleBackgroundParallaxVOffset' => 'Varchar(16)',
        'StyleBackgroundParallaxHOffset' => 'Varchar(16)',
        'StyleBackgroundSize' => "Enum('auto, cover, contain', 'auto')",
        'StyleBackgroundRepeat' => "Enum('repeat, repeat-x, repeat-y, no-repeat', 'repeat')",
        'StyleBackgroundAttachment' => "Enum('scroll, fixed', 'scroll')",
        'StyleBackgroundPositionVertical' => "Enum('top, center, bottom', 'top')",
        'StyleBackgroundPositionHorizontal' => "Enum('left, center, right', 'left')"
    );
    
    private static $has_one = array(
        'StyleBackgroundImage' => 'Image'
    );
    
    private static $defaults = array(
        'StyleBackgroundSize' => 'auto',
        'StyleBackgroundAlpha' => 1,
        'StyleBackgroundRepeat' => 'repeat',
        'StyleBackgroundAttachment' => 'scroll',
        'StyleBackgroundPositionVertical' => 'top',
        'StyleBackgroundPositionHorizontal' => 'left',
        'StyleBackgroundParallax' => 0,
        'StyleBackgroundParallaxRatio' => 0.5,
    );
    
    protected $css = array(
        'background' => 'getStyleBackgroundCSS',
        'background-size' => 'getStyleBackgroundSizeCSS'
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
        
        // Obtain Asset Folder:
        
        $asset_folder = Config::inst()->get('StyleBackgroundExtension', 'asset_folder');
        
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'StyleBackgroundToggle',
                _t('StyleBackgroundExtension.BACKGROUND', 'Background'),
                array(
                    FieldGroup::create(
                        _t('StyleBackgroundExtension.BACKGROUNDCOLOR', 'Background color'),
                        array(
                            ColorField::create('StyleBackgroundColor', ''),
                            DropdownField::create('StyleBackgroundAlpha', '', $alpha)
                        )
                    ),
                    $image = UploadField::create(
                        'StyleBackgroundImage',
                        _t('StyleBackgroundExtension.BACKGROUNDIMAGE', 'Background image')
                    ),
                    DropdownField::create(
                        'StyleBackgroundRepeat',
                        _t('StyleBackgroundExtension.BACKGROUNDREPEAT', 'Background repeat'),
                        $this->owner->dbObject('StyleBackgroundRepeat')->enumValues()
                    ),
                    DropdownField::create(
                        'StyleBackgroundAttachment',
                        _t('StyleBackgroundExtension.BACKGROUNDATTACHMENT', 'Background attachment'),
                        $this->owner->dbObject('StyleBackgroundAttachment')->enumValues()
                    ),
                    FieldGroup::create(
                        _t('StyleBackgroundExtension.BACKGROUNDPOSITION', 'Background position'),
                        array(
                            DropdownField::create(
                                'StyleBackgroundPositionVertical',
                                '',
                                $this->owner->dbObject('StyleBackgroundPositionVertical')->enumValues()
                            ),
                            DropdownField::create(
                                'StyleBackgroundPositionHorizontal',
                                '',
                                $this->owner->dbObject('StyleBackgroundPositionHorizontal')->enumValues()
                            )
                        )
                    ),
                    CheckboxField::create(
                        'StyleBackgroundParallax',
                        _t('StyleBackgroundExtension.USEPARALLAXSCROLLING', 'Use parallax scrolling')
                    ),
                    DisplayLogicWrapper::create(
                        DropdownField::create(
                            'StyleBackgroundParallaxRatio',
                            _t('StyleBackgroundExtension.PARALLAXRATIO', 'Parallax ratio'),
                            $this->getStyleBackgroundParallaxRatios()
                        )->setRightTitle(
                            _t(
                                'StyleBackgroundExtension.PARALLAXRATIORIGHTTITLE',
                                'Percentage of scrolling speed for the parallax effect.'
                            )
                        ),
                        TextField::create(
                            'StyleBackgroundParallaxVOffset',
                            _t('StyleBackgroundExtension.PARALLAXVERTICALOFFSET', 'Parallax vertical offset')
                        ),
                        TextField::create(
                            'StyleBackgroundParallaxHOffset',
                            _t('StyleBackgroundExtension.PARALLAXHORIZONTALOFFSET', 'Parallax horizontal offset')
                        )
                    )->displayIf('StyleBackgroundParallax')->isChecked()->end(),
                    DropdownField::create(
                        'StyleBackgroundSize',
                        _t('StyleBackgroundExtension.BACKGROUNDSIZE', 'Background size'),
                        $this->owner->dbObject('StyleBackgroundSize')->enumValues()
                    )
                )
            )
        );
        
        // Define Field Objects:
        
        $image->setFolderName($asset_folder);
        $image->setAllowedFileCategories('image');
    }
    
    /**
     * Event method called before the extended object is written to the database.
     */
    public function onBeforeWrite()
    {
        $v_offset = $this->owner->StyleBackgroundParallaxVOffset;
        $h_offset = $this->owner->StyleBackgroundParallaxHOffset;
        
        $this->owner->StyleBackgroundParallaxVOffset = SilverWareTools::integer_or_null($v_offset);
        $this->owner->StyleBackgroundParallaxHOffset = SilverWareTools::integer_or_null($h_offset);
    }
    
    /**
     * Answers the CSS string for the background style.
     *
     * @return string
     */
    public function getStyleBackgroundCSS()
    {
        $css = array();
        
        if ($this->owner->StyleBackgroundColor != '' || $this->owner->StyleBackgroundImageID) {
            
            if ($color = $this->owner->getStyleBackgroundColorCSS()) {
                
                $css[] = $color;
                
            } else {
                
                $css[] = "transparent";
                
            }
            
            if ($this->owner->StyleBackgroundImageID) {
                
                $css[] = "url(" . $this->owner->StyleBackgroundImage()->URL  . ")";
                
                
                if ($this->owner->StyleBackgroundParallax) {
                    
                    $css[] = 'no-repeat';
                    $css[] = 'fixed';
                    $css[] = '50% 0';
                    
                } else {
                    
                    $css[] = $this->owner->StyleBackgroundRepeat;
                    $css[] = $this->owner->StyleBackgroundAttachment;
                    $css[] = $this->owner->getStyleBackgroundPositionCSS();
                    
                }
                
            } else {
                
                $css[] = "none";
                
            }
        }
        
        return implode(' ', $css);
    }
    
    /**
     * Answers the CSS string for the background-color style.
     *
     * @return string
     */
    public function getStyleBackgroundColorCSS()
    {
        if ($this->owner->StyleBackgroundColor != '') {
            
            $alpha = $this->owner->StyleBackgroundAlpha;
            $color = $this->owner->dbObject('StyleBackgroundColor');
            
            return ($alpha == 1) ? '#' . $color->getValue() : $color->CSSColor($alpha);
            
        }
    }
    
    /**
     * Answers the CSS string for the background-position style.
     *
     * @return string
     */
    public function getStyleBackgroundPositionCSS()
    {
        $v = $this->owner->StyleBackgroundPositionVertical;
        $h = $this->owner->StyleBackgroundPositionHorizontal;
        
        return ($v == 'center' && $h == 'center') ? "center" : $v . " " . $h;
    }
    
    /**
     * Answers the CSS string for the background-size style.
     *
     * @return string
     */
    public function getStyleBackgroundSizeCSS()
    {
        if ($this->owner->StyleBackgroundSize != 'auto') {
            return $this->owner->StyleBackgroundSize;
        }
    }
    
    /**
     * Updates the attributes of the extended object.
     *
     * @param array $attributes
     */
    public function updateAttributes(&$attributes)
    {
        if ($this->owner->StyleBackgroundParallax) {
            
            $attributes['data-stellar-background-ratio'] = $this->owner->StyleBackgroundParallaxRatio;
            
            if ($v_offset = $this->owner->StyleBackgroundParallaxVOffset) {
                $attributes['data-stellar-vertical-offset'] = $v_offset;
            }
            
            if ($h_offset = $this->owner->StyleBackgroundParallaxHOffset) {
                $attributes['data-stellar-horizontal-offset'] = $h_offset;
            }
        }
    }
    
    /**
     * Answers an array of options for the parallax ratio dropdown field.
     *
     * @return array
     */
    private function getStyleBackgroundParallaxRatios()
    {
        $ratios = array();
        
        for ($i = 0.1; $i <= 0.9; $i += 0.1) {
            
            $ratios["$i"] = ($i * 100) . "%";
            
        }
        
        return $ratios;
    }
}
