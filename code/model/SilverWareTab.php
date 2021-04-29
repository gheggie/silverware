<?php

/**
 * An extension of the data object class for a SilverWare tab.
 */
class SilverWareTab extends DataObject
{
    private static $singular_name = "Tab";
    private static $plural_name   = "Tabs";
    
    private static $default_sort = "Sort";
    
    private static $db = array(
        'Sort' => 'Int',
        'Title' => 'Varchar(255)',
        'Content' => 'HTMLText',
        'Inactive' => 'Boolean',
        'Disabled' => 'Boolean'
    );
    
    private static $defaults = array(
        'Inactive' => 0,
        'Disabled' => 0
    );
    
    private static $has_one = array(
        'Component' => 'SilverWareComponent'
    );
    
    private static $extensions = array(
        'SilverWareFontIconExtension'
    );
    
    private static $summary_fields = array(
        'Title' => 'Title',
        'Inactive.Nice' => 'Inactive',
        'Disabled.Nice' => 'Disabled'
    );
    
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
                TextField::create(
                    'Title',
                    _t('SilverWareTab.TITLE', 'Title')
                ),
                CheckboxField::create(
                    'Inactive',
                    _t('SilverWareTab.INACTIVE', 'Inactive')
                ),
                CheckboxField::create(
                    'Disabled',
                    _t('SilverWareTab.DISABLED', 'Disabled')
                )
            )
        );
        
        // Create Content Tab:
        
        $fields->findOrMakeTab('Root.Content', _t('SilverWareTab.CONTENT', 'Content'));
        
        // Create Content Field:
        
        $fields->addFieldToTab(
            'Root.Content',
            HtmlEditorField::create(
                'Content',
                _t('SilverWareTab.CONTENT', 'Content')
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
                'Title'
            )
        );
    }
    
    /**
     * Answer a unique ID for the receiving tab.
     *
     * @return string
     */
    public function getTabID()
    {
        $segment = URLSegmentFilter::create()->filter($this->Title);
        
        return strtolower(str_replace('_', '-', $this->Component()->getHTMLID())) . '-' . $segment;
    }
    
    /**
     * Answers the anchor for the receiving tab.
     *
     * @return string
     */
    public function getAnchor()
    {
        return '#' . $this->getTabID();
    }
    
    /**
     * Answers true to use fixed width font icons.
     *
     * @return boolean
     */
    public function getFontIconFixedWidth()
    {
        return true;
    }
}
