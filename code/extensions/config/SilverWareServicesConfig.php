<?php

/**
 * An extension of the SilverWare config extension class to add services to site config.
 */
class SilverWareServicesConfig extends SilverWareConfigExtension
{
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects (from parent):
        
        parent::updateCMSFields($fields);
        
        // Create Services Tab:
        
        if (!$fields->fieldByName('Root.SilverWare.Services')) {
            
            $fields->addFieldToTab(
                'Root.SilverWare',
                TabSet::create(
                    'Services',
                    _t('SilverWareServicesConfig.SERVICES', 'Services')
                )
            );
            
        }
    }
}
