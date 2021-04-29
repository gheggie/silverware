<?php

/**
 * An extension of the data object class for a SilverWare page type.
 */
class SilverWarePageType extends DataObject
{
    private static $singular_name = "Page Type";
    private static $plural_name   = "Page Types";
    
    private static $db = array(
        'Type' => 'Varchar(255)'
    );
    
    private static $has_one = array(
        'MyLayout' => 'SilverWareLayout',
        'MyTemplate' => 'SilverWareTemplate',
        'SiteConfig' => 'SiteConfig'
    );
    
    private static $summary_fields = array(
        'PageType' => 'Page Type',
        'MyTemplate.Title' => 'Template',
        'MyLayout.Title' => 'Layout'
    );
    
    private static $default_type = "Page";
    
    /**
     * Answers true if the default record for the receiver has been created.
     *
     * @return boolean
     */
    public static function has_default()
    {
        return self::get()->filter('Type', self::$default_type)->exists();
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
                DropdownField::create(
                    'Type',
                    _t('SilverWarePage.PAGETYPE', 'Page type'),
                    $this->getPageTypes()
                )->setEmptyString(' '),
                DropdownField::create(
                    'MyTemplateID',
                    _t('SilverWarePage.TEMPLATE', 'Template'),
                    SilverWareTemplate::get()->map()
                )->setEmptyString(' '),
                DropdownField::create(
                    'MyLayoutID',
                    _t('SilverWarePage.LAYOUT', 'Layout'),
                    SilverWareLayout::get()->map()
                )->setEmptyString(' ')
            )
        );
        
        // Extend Field Objects:
        
        $this->extend('updateCMSFields', $fields);
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create(
            array(
                'Type',
                'MyTemplateID',
                'MyLayoutID'
            )
        );
    }
    
    /**
     * Answers the title of the receiver for the CMS interface.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->Type;
    }

    /**
     * Answers the i18n singular name of the associated page type.
     *
     * @return string
     */
    public function getPageType()
    {
        if ($this->Type) {
            
            return singleton($this->Type)->i18n_singular_name();
            
        }
    }

    /**
     * Answers an associative array of page types mapped to their i18n singular name.
     *
     * @param boolean $removeExisting Remove page types that already exist in the database.
     * @return array
     */
    protected function getPageTypes($removeExisting = true)
    {
        $types = array();
        
        foreach (ClassInfo::subclassesFor('Page') as $type) {
            
            $types[$type] = singleton($type)->i18n_singular_name();
            
            $existing = self::get()->filter(array('Type' => $type))->first();
            
            if ($removeExisting && $existing) {
                
                if ($existing->ID != $this->ID) {
                    
                    unset($types[$type]);
                    
                }
                
            }
            
        }
        
        asort($types);
        
        return $types;
    }
}
