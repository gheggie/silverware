<?php

/**
 * An extension of the SilverWare config extension class to add page type settings to site config.
 */
class SilverWarePageTypeConfig extends SilverWareConfigExtension
{
    private static $has_many = array(
        'SilverWarePageTypes' => 'SilverWarePageType'
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
        
        // Create Page Types Grid Field:
        
        $page_types = GridField::create(
            'SilverWarePageTypes',
            _t('SilverWarePageTypeConfig.PAGETYPES', 'Page Types'),
            $this->owner->SilverWarePageTypes(),
            $config = GridFieldConfig_RecordEditor::create()
        );
        
        // Create Page Types Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.PageTypes', _t('SilverWarePageTypeConfig.PAGETYPES', 'Page Types'));
        
        // Add Fields to Page Types Tab:
        
        $fields->addFieldToTab('Root.SilverWare.PageTypes', $page_types);
    }
    
    /**
     * Answers the type object for the given page class.
     *
     * @param string $className
     * @return SilverWarePageType
     */
    public function getTypeForPageClass($className)
    {
        return $this->owner->SilverWarePageTypes()->filter(array('Type' => $className))->first();
    }
    
    /**
     * Answers the template object for the given page.
     *
     * @param SiteTree $Page
     * @return SilverWareTemplate
     */
    public function getTemplateForPage(SiteTree $Page)
    {
        foreach (array_reverse(ClassInfo::ancestry($Page->ClassName)) as $className) {
            
            if ($Type = $this->getTypeForPageClass($className)) {
                
                if ($Type->MyTemplate()->isInDB()) {
                    return $Type->MyTemplate();
                }
                
            }
            
            if ($className == 'SiteTree') {
                break;
            }
            
        }
        
        return SilverWareTemplate::create();
    }

    /**
     * Answers the layout object for the given page.
     *
     * @param SiteTree $Page
     * @return SilverWareLayout
     */
    public function getLayoutForPage(SiteTree $Page)
    {
        foreach (array_reverse(ClassInfo::ancestry($Page->ClassName)) as $className) {
            
            if ($Type = $this->getTypeForPageClass($className)) {
                
                if ($Type->MyLayout()->isInDB()) {
                    return $Type->MyLayout();
                }
                
            }
            
            if ($className == 'SiteTree') {
                break;
            }
            
        }
        
        return SilverWareLayout::create();
    }
}
