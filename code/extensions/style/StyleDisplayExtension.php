<?php

/**
 * An extension of the SilverWare style extension class to apply display styles to the extended object.
 */
class StyleDisplayExtension extends SilverWareStyleExtension
{
    private static $db = array(
        'HideOn' => 'Varchar(32)',
        'ShowOn' => 'Varchar(32)'
    );
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'StyleDisplayToggle',
                _t('StyleDisplayExtension.DISPLAY', 'Display'),
                array(
                    DropdownField::create(
                        'HideOn',
                        _t('StyleDisplayExtension.HIDE', 'Hide on'),
                        SilverWareGrid::inst()->getDeviceOptions()
                    )->setEmptyString(' '),
                    DropdownField::create(
                        'ShowOn',
                        _t('StyleDisplayExtension.SHOW', 'Show on'),
                        SilverWareGrid::inst()->getDeviceOptions()
                    )->setEmptyString(' '),
                )
            )
        );
    }
}
