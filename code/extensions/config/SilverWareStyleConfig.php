<?php

/**
 * An extension of the SilverWare config extension class to add style settings to site config.
 */
class SilverWareStyleConfig extends SilverWareConfigExtension
{
    private static $db = array(
        'SilverWareStyleCache' => 'Boolean',
        'SilverWareStyleDebug' => 'Boolean',
        'SilverWareStyleCacheLifetime' => 'Int'
    );
    
    private static $has_many = array(
        'SilverWareStyles' => 'SilverWareStyle',
        'SilverWareStyleSheets' => 'SilverWareStyleSheet'
    );
    
    private static $defaults = array(
        'SilverWareStyleCache' => 0,
        'SilverWareStyleDebug' => 0,
        'SilverWareStyleCacheLifetime' => 300
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
        
        // Create Style Tab:
        
        if (!$fields->fieldByName('Root.SilverWare.Style')) {
            
            $fields->addFieldToTab(
                'Root.SilverWare',
                TabSet::create(
                    'Style',
                    _t('SilverWareStyleConfig.STYLE', 'Style')
                )
            );
            
        }
        
        // Create Editor Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.Style.Editor', _t('SilverWareStyleConfig.EDITOR', 'Editor'));
        
        // Create Styles Grid Field:
        
        $styles = GridField::create(
            'SilverWareStyles',
            _t('SilverWareStyleConfig.STYLES', 'Styles'),
            $this->owner->SilverWareStyles(),
            GridFieldConfig_MultiClassEditor::create()->useDescendantsOf('SilverWareStyle')
        );
        
        // Add Fields to Editor Tab:
        
        $fields->addFieldToTab('Root.SilverWare.Style.Editor', $styles);
        
        // Create Sheets Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.Style.Sheets', _t('SilverWareStyleConfig.SHEETS', 'Sheets'));
        
        // Create Sheets Grid Field:
        
        $sheets = GridField::create(
            'SilverWareStyleSheets',
            _t('SilverWareStyleConfig.Sheets', 'Sheets'),
            $this->owner->SilverWareStyleSheets(),
            GridFieldConfig_OrderableEditor::create()
        );
        
        // Add Fields to Sheets Tab:
        
        $fields->addFieldToTab('Root.SilverWare.Style.Sheets', $sheets);
        
        // Create Options Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.Style.Options', _t('SilverWareStyleConfig.OPTIONS', 'Options'));
        
        // Create Options Fields:
        
        $fields->addFieldsToTab(
            'Root.SilverWare.Style.Options',
            array(
                ToggleCompositeField::create(
                    'SilverWareStyleCacheOptions',
                    _t('SilverWareStyleConfig.CACHE', 'Cache'),
                    array(
                        NumericField::create(
                            'SilverWareStyleCacheLifetime',
                            _t('SilverWareStyleConfig.CACHELIFETIME', 'Cache lifetime (in seconds)')
                        ),
                        CheckboxField::create(
                            'SilverWareStyleCache',
                            _t('SilverWareStyleConfig.ENABLESTYLECACHE', 'Enable style cache')
                        )
                    )
                ),
                ToggleCompositeField::create(
                    'SilverWareStyleDebugOptions',
                    _t('SilverWareStyleConfig.DEBUG', 'Debug'),
                    array(
                        CheckboxField::create(
                            'SilverWareStyleDebug',
                            _t('SilverWareStyleConfig.ENABLESTYLEDEBUGGING', 'Enable style debugging')
                        )
                    )
                )
            )
        );
    }
    
    /**
     * Answers a list of enabled style sheets.
     *
     * @return DataList
     */
    public function getEnabledStyleSheets()
    {
        return $this->owner->SilverWareStyleSheets()->filter('Disabled', 0);
    }
    
    /**
     * Answers true if style caching is enabled.
     *
     * @return boolean
     */
    public function isStyleCacheEnabled()
    {
        return $this->owner->SilverWareStyleCache;
    }
    
    /**
     * Answers true if style debugging is enabled.
     *
     * @return boolean
     */
    public function isStyleDebugEnabled()
    {
        return $this->owner->SilverWareStyleDebug;
    }
}
