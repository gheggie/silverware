<?php

/**
 * An extension of the data object class for a device style.
 */
class DeviceStyle extends DataObject
{
    private static $singular_name = "Device Style";
    private static $plural_name   = "Device Styles";
    
    private static $default_sort = "MinWidth";
    
    private static $db = array(
        'Device' => 'Varchar(32)',
        'MinWidth' => 'Int',
        'Disabled' => 'Boolean'
    );
    
    private static $has_one = array(
        'Style' => 'SilverWareStyle'
    );
    
    private static $has_many = array(
        'Rules' => 'StyleRule'
    );
    
    private static $defaults = array(
        'MinWidth' => 0,
        'Disabled' => 0
    );
    
    private static $summary_fields = array(
        'DeviceName' => 'Device',
        'MinWidthLabel' => 'Minimum Width',
        'EnabledRules' => 'Rules',
        'Disabled.Nice' => 'Disabled'
    );
    
    private static $identifier_mappings = array(
        'Rules' => array(
            'Component' => 'ComponentRule',
            'Content' => 'ContentRule',
            'Image' => 'ImageRule',
            'Link' => 'LinkRule',
            'LinkHover' => 'LinkHoverRule',
            'Title' => 'TitleRule',
            'TitleText' => 'TitleTextRule',
            'Heading' => array(
                'class' => 'HeadingRule',
                'params' => array('ApplyToTags')
            )
        )
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
                CheckboxField::create(
                    'Disabled',
                    _t('DeviceStyle.DISABLED', 'Disabled')
                )
            )
        );
        
        // Create Rules Fields (if saved):
        
        if ($this->ID) {
            
            // Create Rules Tab:
            
            $fields->findOrMakeTab('Root.Rules', _t('DeviceStyle.RULES', 'Rules'));
            
            // Add Rules Grid Field to Tab:
            
            $fields->addFieldToTab(
                'Root.Rules',
                GridField::create(
                    'Rules',
                    _t('DeviceStyle.RULES', 'Rules'),
                    $this->Rules(),
                    GridFieldConfig_MultiClassEditor::create()->useDescendantsOf('BaseRule')
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
                'Device'
            )
        );
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Call Parent Event:
        
        parent::onBeforeWrite();
        
        // Define Min Width Value:
        
        $this->MinWidth = SilverWareGrid::inst()->getDeviceMinWidth($this->Device);
    }
    
    /**
     * Event method called before an instance of the receiver is created.
     *
     * @param SilverWareBlueprint $blueprint
     */
    public function onBeforeCreate(SilverWareBlueprint $blueprint)
    {
        // Call Parent Event:
        
        parent::onBeforeCreate($blueprint);
        
        // Update Identifier:
        
        if (SilverWareGrid::inst()->isDefaultDeviceName($blueprint->getIdentifier())) {
            $blueprint->setIdentifier(SilverWareGrid::inst()->getDefaultDeviceName());
        }
        
        // Define Device Data (if required):
        
        if (!$blueprint->hasData('Device')) {
            $blueprint->addData('Device', $blueprint->getIdentifier());
        }
    }
    
    /**
     * Defines the value of the device property.
     *
     * @param string $device
     * @return DeviceStyle
     */
    public function setDevice($device)
    {
        if (SilverWareGrid::inst()->isDeviceName($device)) {
            $this->setField('Device', SilverWareGrid::inst()->clean($device));
        }
        
        return $this;
    }
    
    /**
     * Answers a string describing the type of device style.
     *
     * @return string
     */
    public function getType()
    {
        return $this->DeviceName . " " . _t('DeviceStyle.STYLE', 'Style');
    }
    
    /**
     * Answers the title of the receiver.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getType();
    }
    
    /**
     * Answers a string describing the enabled rules within the style.
     *
     * @return string
     */
    public function getEnabledRules()
    {
        $types = array();
        
        foreach ($this->Rules()->filter('Disabled', 0) as $Rule) {
            $types[$Rule->getShortType()] = 1;
        }
        
        if (!empty($types)) {
            return implode(', ', array_keys($types));
        }
        
        return _t('DeviceStyle.NONE', 'None');
    }
    
    /**
     * Answers the name of the device.
     *
     * @return string
     */
    public function getDeviceName()
    {
        return SilverWareGrid::inst()->getDeviceName($this->Device);
    }
    
    /**
     * Answers the options for the device dropdown field.
     *
     * @return array
     */
    public function getDeviceOptions()
    {
        return $this->Style()->getDeviceOptions();
    }
    
    /**
     * Answers the minimum width label for the selected device.
     *
     * @return string
     */
    public function getMinWidthLabel()
    {
        if ($width = $this->MinWidth) {
            return _t('DeviceStyle.MINWIDTHLABEL', '{width} pixels', '', array('width' => $width));
        }
        
        return _t('DeviceStyle.NONE', 'None');
    }
    
    /**
     * Answers true if the style is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return !$this->Disabled;
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
        
        // Generate CSS (if enabled):
        
        if ($this->isEnabled()) {
            
            // Add Debug Information (if enabled):
            
            if ($this->Style()->isDebugEnabled()) {
                $css[] = "/* == " . $this->Type . " == */\n";
            }
            
            // Merge Media Query Start:
            
            $css[] = $this->getMediaQueryStart();
            
            // Merge CSS from Rules:
            
            $css = array_merge($css, $this->getRulesCSS($prefixes));
            
            // Merge Media Query End:
            
            $css[] = $this->getMediaQueryEnd();
            
            // Filter CSS Array:
            
            $css = array_filter($css);
            
        }
        
        // Answer CSS Array:
        
        return $css;
    }
    
    /**
     * Answers an array of custom CSS generated by the rules for this device.
     *
     * @param array $prefixes
     * @return array
     */
    public function getRulesCSS($prefixes = array())
    {
        $css = array();
        
        foreach ($this->Rules()->filter('Disabled', 0) as $Rule) {
            
            $css = array_merge($css, $this->indent($Rule->getCustomCSS($prefixes)));
            
        }
        
        return $css;
    }
    
    /**
     * Answers true if the receiver has a media query.
     *
     * @return boolean
     */
    public function hasMediaQuery()
    {
        return ($this->MinWidth > 0);
    }
    
    /**
     * Answers the start of the media query from the receiver.
     *
     * @return string
     */
    public function getMediaQueryStart()
    {
        if ($this->hasMediaQuery()) {
            return "@media (min-width: {$this->MinWidth}px) {\n";
        }
    }
    
    /**
     * Answers the start of the media query from the receiver.
     *
     * @return string
     */
    public function getMediaQueryEnd()
    {
        if ($this->hasMediaQuery()) {
            return "}\n";
        }
    }
    
    /**
     * Answers an array of prefixes for the components associated with this style.
     *
     * @return array
     */
    public function getPrefixes()
    {
        return $this->Style()->getPrefixes();
    }
    
    /**
     * Indents the given CSS array to the specified number of spaces.
     *
     * @param array $css
     * @param integer $spaces
     * @return array
     */
    protected function indent($css = array(), $spaces = 2)
    {
        if ($this->hasMediaQuery()) {
            return SilverWareTools::indent_css($css, $spaces);
        }
        
        return $css;
    }
}
