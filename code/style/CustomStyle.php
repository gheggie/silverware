<?php

/**
 * An extension of the SilverWare style for a custom style.
 */
class CustomStyle extends SilverWareStyle
{
    private static $singular_name = "Custom Style";
    private static $plural_name   = "Custom Styles";
    
    private static $db = array(
        'StyleType' => 'Varchar(128)'
    );
    
    private static $has_many = array(
        'Devices' => 'CustomDeviceStyle',
        'LinkedStyles' => 'LinkedStyle'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Field Objects:
        
        $fields->addFieldToTab(
            'Root.Main',
            DropdownField::create(
                'StyleType',
                _t('CustomStyle.TYPE', 'Type'),
                $this->getStyleTypes()
            )->setEmptyString(' ')
        );
        
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
        return parent::getCMSValidator()->addRequiredField('StyleType');
    }
    
    /**
     * Answers a string describing the type of style.
     *
     * @return string
     */
    public function getType()
    {
        if ($this->StyleType) {
            return _t('CustomStyle.CUSTOMSTYLE', '{type} (Custom Style)', '', array('type' => $this->getTypeLabel()));
        }
        
        return parent::getType();
    }
    
    /**
     * Answers a label for the style type.
     *
     * @return string
     */
    public function getTypeLabel()
    {
        return FormField::name_to_label($this->StyleType);
    }
    
    /**
     * Answers a map of the available style types.
     *
     * @return array
     */
    public function getStyleTypes()
    {
        $map = array();
        
        if ($config = $this->getStyleTypesConfig()) {
            
            foreach ($config as $type => $details) {
                
                $map[$type] = isset($details['name']) ? $details['name'] : FormField::name_to_label($type);
                
            }
            
        }
        
        return $map;
    }
    
    /**
     * Answers a map of the editable rule types for this style and the given device style.
     *
     * @param CustomDeviceStyle $Device
     * @return array
     */
    public function getEditableRuleTypes(CustomDeviceStyle $Style)
    {
        $map = array();
        
        if ($config = $this->getEditableRulesConfig($this->StyleType)) {
            
            foreach ($config as $type => $details) {
                
                // Get Rule Name:
                
                $name = isset($details['name']) ? $details['name'] : $type;
                
                // Check Device:
                
                if ($Style->Device && isset($details['devices'])) {
                    
                    if ($devices = $this->cleanDevices($details['devices'])) {
                        
                        if (!in_array($Style->Device, $devices)) {
                            continue;
                        }
                        
                    }
                    
                }
                
                // Define Rule:
                
                $map[$type] = $name;
                
            }
            
        }
        
        return $map;
    }
    
    /**
     * Answers an array of prefixes for the components associated with this style.
     *
     * @return array
     */
    public function getPrefixes()
    {
        $prefixes = array();
        
        foreach ($this->ValidLinkedStyles() as $Style) {
            $prefixes[] = $Style->getPrefix();
        }
        
        return $prefixes;
    }
    
    /**
     * Answers the options for the device dropdown field.
     *
     * @return array
     */
    public function getDeviceOptions()
    {
        $options = parent::getDeviceOptions();
        
        if ($config = $this->getStyleConfig($this->StyleType)) {
            
            if (isset($config['devices'])) {
                
                if ($devices = $this->cleanDevices($config['devices'])) {
                    
                    $options = array_filter($options, function ($key) use ($devices) {
                        return in_array($key, $devices);
                    }, ARRAY_FILTER_USE_KEY);
                    
                }
                
            }
            
        }
        
        return $options;
    }
    
    /**
     * Answers the mapped attribute with the given name.
     *
     * @param string $name
     * @return string
     */
    public function getMappedAttribute($name)
    {
        if ($Link = $this->ValidLinkedStyles()->filter('StyleID', $this->ID)->first()) {
            return $Link->getMappedAttribute($name);
        }
    }
    
    /**
     * Answers the selector for the given rule.
     *
     * @param CustomRule $rule
     * @return string|array
     */
    public function getSelectorForRule(CustomRule $rule)
    {
        if ($config = $this->getRulesConfig($this->StyleType)) {
            
            if (isset($config[$rule->RuleType]['selector'])) {
                return $config[$rule->RuleType]['selector'];
            }
            
        }
    }
    
    /**
     * Answers a list of non-editable rule objects defined by configuration.
     *
     * @param CustomDeviceStyle $Style
     * @return ArrayList
     */
    public function NonEditableRules(CustomDeviceStyle $Style)
    {
        $rules = ArrayList::create();
        
        foreach ($this->getNonEditableRulesConfig($this->StyleType) as $config) {
            
            $rule = NonEditableRule::create();
            
            $rule->DeviceID = $Style->ID;
            
            $rule->setSelector($config['selector']);
            $rule->setMappings($config['mappings']);
            
            $rules->push($rule);
            
        }
        
        return $rules;
    }
    
    /**
     * Answers a list of valid linked style objects.
     *
     * @return DataList
     */
    public function ValidLinkedStyles()
    {
        return $this->LinkedStyles()->filter('LinkedToID:GreaterThan', 0);
    }
    
    /**
     * Answers the style types configuration for the receiver.
     *
     * @return array
     */
    protected function getStyleTypesConfig()
    {
        return $this->config()->get('types');
    }
    
    /**
     * Answers the style configuration for the specified type.
     *
     * @param string $type
     * @return array
     */
    protected function getStyleConfig($type)
    {
        $config = array();
        
        if ($types = $this->getStyleTypesConfig()) {
            
            if (isset($types[$type])) {
                $config = $types[$type];
            }
            
        }
        
        return $config;
    }
    
    /**
     * Answers the rules configuration for the specified style type.
     *
     * @param string $type
     * @return array
     */
    protected function getRulesConfig($type)
    {
        $config = array();
        
        if ($style = $this->getStyleConfig($type)) {
            
            if (isset($style['rules']) && is_array($style['rules'])) {
                $config = $style['rules'];
            }
            
        }
        
        return $config;
    }
    
    /**
     * Answers the editable rules configuration for the specified style type.
     *
     * @param string $type
     * @return array
     */
    protected function getEditableRulesConfig($type)
    {
        $config = array();
        
        foreach ($this->getRulesConfig($type) as $rule => $details) {
            
            if (!isset($details['editable']) || $details['editable']) {
                $config[$rule] = $details;
            }
            
        }
        
        return $config;
    }
    
    /**
     * Answers the non-editable rules configuration for the specified style type.
     *
     * @param string $type
     * @return array
     */
    protected function getNonEditableRulesConfig($type)
    {
        $config = array();
        
        foreach ($this->getRulesConfig($type) as $rule => $details) {
            
            if (isset($details['editable']) && !$details['editable']) {
                $config[$rule] = $details;
            }
            
        }
        
        return $config;
    }
    
    /**
     * Cleans the given array of device names, or device group name.
     *
     * @param array|string $devices
     * @return array
     */
    protected function cleanDevices($devices)
    {
        // Create Cleaned Array:
        
        $cleaned = array();
        
        // Check Argument Type:
        
        if (!is_array($devices)) {
            $devices = SilverWareGrid::inst()->getDeviceGroup($devices);
        }
        
        // Iterate Devices:
        
        foreach ($devices as $device) {
            
            if (SilverWareGrid::inst()->isDeviceName($device)) {
                $cleaned[] = $this->cleanDeviceName($device);
            } elseif (SilverWareGrid::inst()->isDefaultDeviceName($device)) {
                $cleaned[] = $this->cleanDeviceName(SilverWareGrid::inst()->getDefaultDeviceKey());
            }
            
        }
        
        // Answer Cleaned Array:
        
        return $cleaned;
    }
    
    /**
     * Cleans the given device name.
     *
     * @param string $name
     * @return string
     */
    public function cleanDeviceName($name)
    {
        return SilverWareGrid::inst()->clean($name);
    }
}
