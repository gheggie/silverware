<?php

/**
 * An extension of the data object class for a SilverWare style sheet.
 */
class SilverWareStyleSheet extends DataObject
{
    private static $singular_name = "Style Sheet";
    private static $plural_name   = "Style Sheets";
    
    private static $default_sort = "Sort";
    
    private static $asset_folder = "Style/Sheets";
    
    private static $db = array(
        'Sort' => 'Int',
        'Name' => 'Varchar(255)',
        'Disabled' => 'Boolean'
    );
    
    private static $has_one = array(
        'File' => 'File',
        'SiteConfig' => 'SiteConfig'
    );
    
    private static $defaults = array(
        'Disabled' => 0
    );
    
    private static $summary_fields = array(
        'Name' => 'Name',
        'Path' => 'Path',
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
                    'Name',
                    _t('SilverWareStyleSheet.NAME', 'Name')
                ),
                CheckboxField::create(
                    'Disabled',
                    _t('SilverWareStyleSheet.DISABLED', 'Disabled')
                )
            )
        );
        
        // Create File Tab:
        
        $fields->findOrMakeTab('Root.File', _t('SilverWareStyleSheet.FILE', 'File'));
        
        // Create Upload Field:
        
        $fields->addFieldToTab(
            'Root.File',
            $upload = UploadField::create(
                'File',
                _t('SilverWareStyleSheet.FILE', 'File (*.css)')
            )
        );
        
        // Define Upload Field:
        
        $upload->setAllowedExtensions(array('css'));
        $upload->setFolderName($this->config()->asset_folder);
        $upload->setRightTitle(
            _t(
                'SilverWareStyleSheet.FILERIGHTTITLE',
                'Upload a cascading style sheet file with extension *.css'
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
                'Name',
                'File'
            )
        );
    }
    
    /**
     * Answers the path of the attached style sheet file.
     *
     * @return string
     */
    public function getPath()
    {
        if ($this->HasFile()) {
            
            return $this->File()->Filename;
            
        }
    }
    
    /**
     * Registers the style sheet for loading via the requirements class.
     */
    public function load()
    {
        if ($this->HasFile()) {
            
            Requirements::css($this->getPath());
            
        }
    }
    
    /**
     * Answers true if the receiver has a file.
     *
     * @return boolean
     */
    public function HasFile()
    {
        return $this->File()->exists();
    }
}
