<?php

/**
 * An extension of the SilverWare config extension class to add advanced settings to site config.
 */
class SilverWareAdvancedConfig extends SilverWareConfigExtension
{
    private static $db = array(
        'SilverWareImageAutoRotate' => 'Boolean'
    );
    
    private static $defaults = array(
        'SilverWareImageAutoRotate' => 1
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
        
        // Create Advanced Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.Advanced', _t('SilverWareAdvancedConfig.ADVANCED', 'Advanced'));
        
        // Add Fields to Advanced Tab:
        
        $fields->addFieldsToTab(
            'Root.SilverWare.Advanced',
            array(
                ToggleCompositeField::create(
                    'AdvancedImagesToggle',
                    _t('SilverWareAdvancedConfig.IMAGES', 'Images'),
                    array(
                        CheckboxField::create(
                            'SilverWareImageAutoRotate',
                            _t(
                                'SilverWareAdvancedConfig.AUTOMATICALLYROTATEUPLOADEDIMAGES',
                                'Automatically rotate uploaded images'
                            )
                        )
                    )
                )
            )
        );
    }
}
