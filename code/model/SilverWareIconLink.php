<?php

/**
 * An extension of the SilverWare link class for an icon link.
 */
class SilverWareIconLink extends SilverWareLink
{
    private static $singular_name = "Icon";
    private static $plural_name   = "Icons";
    
    private static $db = array(
        'IconColor' => 'Color',
        'IconHoverColor' => 'Color',
        'IconActiveColor' => 'Color',
        'IconBackgroundColor' => 'Color',
        'IconHoverBackgroundColor' => 'Color',
        'IconActiveBackgroundColor' => 'Color'
    );
    
    private static $summary_fields = array(
        'Type' => 'Type',
        'Name' => 'Name',
        'Link' => 'Link',
        'Disabled.Nice' => 'Disabled'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Load Requirements:
        
        Requirements::css(SILVERWARE_DIR . '/css/cms/cms-icon-link.css');
        
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Modify Field Objects:
        
        $fields->dataFieldByName('FontIconSize')->setEmptyString(_t('SilverWareIconLink.DEFAULT', 'Default'));
        
        // Remove Field Objects:
        
        $fields->removeByName('FontIconSize');
        $fields->removeByName('FontIconColor');
        $fields->removeByName('FontIconAlpha');
        
        // Create Field Objects:
        
        $fields->addFieldsToTab(
            'Root.Icon',
            array(
                FieldGroup::create(
                    _t('SilverWareIconLink.ICONCOLORS', 'Icon colors'),
                    array(
                        ColorField::create(
                            'IconColor',
                            ''
                        )->setAttribute(
                            'placeholder',
                            _t('SilverWareIconLink.ICON', 'Icon')
                        ),
                        ColorField::create(
                            'IconHoverColor',
                            ''
                        )->setAttribute(
                            'placeholder',
                            _t('SilverWareIconLink.HOVER', 'Hover')
                        ),
                        ColorField::create(
                            'IconActiveColor',
                            ''
                        )->setAttribute(
                            'placeholder',
                            _t('SilverWareIconLink.ACTIVE', 'Active')
                        )
                    )
                ),
                FieldGroup::create(
                    _t('SilverWareIconLink.ICONBACKGROUNDCOLORS', 'Icon background colors'),
                    array(
                        ColorField::create(
                            'IconBackgroundColor',
                            ''
                        )->setAttribute(
                            'placeholder',
                            _t('SilverWareIconLink.ICON', 'Icon')
                        ),
                        ColorField::create(
                            'IconHoverBackgroundColor',
                            ''
                        )->setAttribute(
                            'placeholder',
                            _t('SilverWareIconLink.HOVER', 'Hover')
                        ),
                        ColorField::create(
                            'IconActiveBackgroundColor',
                            ''
                        )->setAttribute(
                            'placeholder',
                            _t('SilverWareIconLink.ACTIVE', 'Active')
                        )
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a string describing the type of item.
     *
     * @return string
     */
    public function getType()
    {
        return $this->i18n_singular_name();
    }
    
    /**
     * Answers false to disable fixed width font icons.
     *
     * @return boolean
     */
    public function getFontIconFixedWidth()
    {
        return false;
    }
    
    /**
     * Answers true if the receiver has colors defined.
     *
     * @return boolean
     */
    public function HasColors()
    {
        return ($this->IconColor && $this->IconBackgroundColor);
    }
    
    /**
     * Answers true if the receiver has hover colors defined.
     *
     * @return boolean
     */
    public function HasHoverColors()
    {
        return ($this->IconHoverColor && $this->IconHoverBackgroundColor);
    }
    
    /**
     * Answers true if the receiver has active colors defined.
     *
     * @return boolean
     */
    public function HasActiveColors()
    {
        return ($this->IconActiveColor && $this->IconActiveBackgroundColor);
    }
}
