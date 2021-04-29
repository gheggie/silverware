<?php

/**
 * An extension of the SilverWare config extension class to add font settings to site config.
 */
class SilverWareFontConfig extends SilverWareConfigExtension
{
    private static $has_many = array(
        'SilverWareFonts' => 'SilverWareFont'
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
        
        // Create Fonts Grid Field:
        
        $fonts = GridField::create(
            'SilverWareFonts',
            _t('SilverWareFontConfig.FONTS', 'Fonts'),
            $this->owner->SilverWareFonts(),
            $config = GridFieldConfig_RecordEditor::create()
        );
        
        // Create Fonts Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.Fonts', _t('SilverWareFontConfig.FONTS', 'Fonts'));
        
        // Add Fields to Fonts Tab:
        
        $fields->addFieldToTab('Root.SilverWare.Fonts', $fonts);
    }
}
