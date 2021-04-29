<?php

/**
 * An extension of the data object class for a SilverWare style.
 */
class SilverWareStyle extends DataObject implements Flushable
{
    private static $singular_name = "Style";
    private static $plural_name   = "Styles";
    
    private static $default_sort = "Sort";
    
    private static $db = array(
        'Sort' => 'Int',
        'Name' => 'Varchar(255)'
    );
    
    private static $has_one = array(
        'SiteConfig' => 'SiteConfig'
    );
    
    private static $has_many = array(
        'Devices' => 'DeviceStyle'
    );
    
    private static $summary_fields = array(
        'Type' => 'Type',
        'Name' => 'Name',
        'EnabledDevices' => 'Devices'
    );
    
    /**
     * Clears all cached styles upon flush.
     */
    public static function flush()
    {
        SS_Cache::factory(__CLASS__)->clean(Zend_Cache::CLEANING_MODE_ALL);
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
                TextField::create(
                    'Name',
                    _t('SilverWareStyle.NAME', 'Name')
                )
            )
        );
        
        // Create Devices Fields (if saved):
        
        if ($this->ID) {
            
            // Create Devices Tab:
            
            $fields->findOrMakeTab('Root.Devices', _t('SilverWareStyle.DEVICES', 'Devices'));
            
            // Create Grid Field Config:
            
            $devicesConfig = GridFieldConfig_RecordEditor::create();
            
            // Obtain Edit Form Component:
            
            $editComponent = $devicesConfig->getComponentByType('GridFieldDetailForm');
            
            // Pass $this as $self (compatibility with PHP 5.3):
            
            $self = $this;
            
            // Define Edit Form Callback:
            
            $editComponent->setItemEditFormCallback(function ($form, $itemRequest) use ($self) {
                
                // Obtain Child Record:
                
                $record = $form->getRecord();
                
                // Add Dropdown Field:
                
                $form->Fields()->addFieldToTab(
                    'Root.Main',
                    DropdownField::create(
                        'Device',
                        _t('DeviceStyle.DEVICE', 'Device'),
                        $self->getDeviceOptions(),
                        $record->Device
                    ),
                    'Disabled'
                );
                
            });
            
            // Add Devices Grid Field to Tab:
            
            $fields->addFieldToTab(
                'Root.Devices',
                GridField::create(
                    'Devices',
                    _t('SilverWareStyle.DEVICES', 'Devices'),
                    $this->Devices(),
                    $devicesConfig
                )
            );
            
        }
        
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
                'Name'
            )
        );
    }
    
    /**
     * Answers a string describing the type of style.
     *
     * @return string
     */
    public function getType()
    {
        return $this->i18n_singular_name();
    }
    
    /**
     * Answers an array of prefixes for the components associated with this style.
     *
     * @return array
     */
    public function getPrefixes()
    {
        return array();
    }
    
    /**
     * Answers the options for the device dropdown field.
     *
     * @return array
     */
    public function getDeviceOptions()
    {
        return SilverWareGrid::inst()->getDeviceOptions();
    }
    
    /**
     * Answers an array of custom CSS required for the template.
     *
     * @param array $prefixes
     * @return array
     */
    public function getCustomCSS($prefixes = array())
    {
        // Create CSS Array:
        
        $css = array();
        
        // Create Device CSS Array:
        
        $device_css = array();
        
        // Load Cached Device CSS:
        
        if ($this->isCacheEnabled()) {
            $device_css = $this->getStyleCache()->load($this->ID);
        }
        
        // Add Debug Information (if enabled):
        
        if ($this->isDebugEnabled()) {
            
            $lifetime = $this->getCacheLifetime();
            
            $cached = ($this->isCacheEnabled() && !empty($device_css)) ? " (from cache; lifetime: {$lifetime})" : '';
            
            $css[] = "/* == " . $this->Type . ": " . $this->Name . "{$cached} == */\n";
            
        }
        
        // Create Device CSS (if does not exist):
        
        if (empty($device_css)) {
            
            // Create Device CSS Array:
            
            $device_css = array();
            
            // Merge CSS from Devices:
            
            foreach ($this->Devices()->filter('Disabled', 0) as $Device) {
                $device_css = array_merge($device_css, $Device->getCustomCSS($prefixes));
            }
            
            // Save CSS to Style Cache:
            
            if ($this->isCacheEnabled()) {
                $this->getStyleCache()->save($device_css, $this->ID);
            }
            
        }
        
        // Merge Device CSS:
        
        $css = array_merge($css, $device_css);
        
        // Filter CSS Array:
        
        $css = array_filter($css);
        
        // Answer CSS Array:
        
        return $css;
    }
    
    /**
     * Answers a string describing the enabled devices within the style.
     *
     * @return string
     */
    public function getEnabledDevices()
    {
        $types = array();
        
        foreach ($this->Devices()->filter('Disabled', 0) as $Device) {
            $types[$Device->DeviceName] = 1;
        }
    
        if (!empty($types)) {
            return implode(', ', array_keys($types));
        }
        
        return _t('SilverWareStyle.NONE', 'None');
    }
    
    /**
     * Answers true if style caching is enabled.
     *
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return $this->SiteConfig()->isStyleCacheEnabled();
    }
    
    /**
     * Answers the lifetime for the cache in seconds.
     *
     * @return integer
     */
    public function getCacheLifetime()
    {
        return $this->SiteConfig()->SilverWareStyleCacheLifetime;
    }
    
    /**
     * Answers true if style debugging is enabled.
     *
     * @return boolean
     */
    public function isDebugEnabled()
    {
        return $this->SiteConfig()->isStyleDebugEnabled();
    }
    
    /**
     * Event method called before the receiver is deleted from the database.
     */
    protected function onBeforeDelete()
    {
        // Call Parent Event:
        
        parent::onBeforeDelete();
        
        // Delete Linked Styles:
        
        LinkedStyle::get()->filter('StyleID', $this->ID)->removeAll();
    }
    
    /**
     * Answers the style cache for the receiver.
     *
     * @return Zend_Cache_Core
     */
    protected function getStyleCache()
    {
        return SS_Cache::factory(
            __CLASS__,
            'Output',
            array(
                'lifetime' => $this->getCacheLifetime(),
                'automatic_serialization' => true
            )
        );
    }
}
