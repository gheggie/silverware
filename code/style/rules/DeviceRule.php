<?php

/**
 * An extension of the style rule class for a device rule.
 */
class DeviceRule extends StyleRule
{
    private static $singular_name = "Device Rule";
    private static $plural_name   = "Device Rules";
    
    private static $default_sort = "MinWidth";
    
    private static $db = array(
        'Device' => 'Varchar(32)',
        'MinWidth' => 'Int'
    );
    
    private static $has_one = array(
        'Component' => 'SilverWareComponent'
    );
    
    private static $defaults = array(
        'MinWidth' => 0
    );
    
    private static $summary_fields = array(
        'DeviceName' => 'Device',
        'MinWidthLabel' => 'Minimum Width',
        'Disabled.Nice' => 'Disabled'
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
                'Device',
                _t('DeviceRule.DEVICE', 'Device'),
                SilverWareGrid::inst()->getDeviceOptions()
            ),
            'Name'
        );
        
        // Remove Field Objects:
        
        $fields->removeByName('Name');
        $fields->removeByName('State');
        
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
        
        // Define Identifier:
        
        if (!$this->Identifier) {
            $this->setIdentifier($this->Device ? $this->Device : StyleHandler::get_default_device_name());
        }
        
        // Define Min Width Value:
        
        $this->MinWidth = SilverWareGrid::inst()->getDeviceMinWidth($this->Device);
    }
    
    /**
     * Defines the value of the device property.
     *
     * @param string $device
     * @return DeviceRule
     */
    public function setDevice($device)
    {
        if (SilverWareGrid::inst()->isDeviceName($device)) {
            $this->setField('Device', SilverWareGrid::inst()->clean($device));
        }
        
        return $this;
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
     * Answers the minimum width label for the rule.
     *
     * @return string
     */
    public function getMinWidthLabel()
    {
        if ($width = $this->MinWidth) {
            return _t('DeviceRule.MINWIDTHLABEL', '{width} pixels', '', array('width' => $width));
        }
        
        return _t('DeviceRule.NONE', 'None');
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
     * Answers an array of CSS to merge before the rule CSS.
     *
     * @return array
     */
    public function getPreRuleCSS()
    {
        return array($this->getMediaQueryStart());
    }
    
    /**
     * Answers an array of CSS to merge after the rule CSS.
     *
     * @return array
     */
    public function getPostRuleCSS()
    {
        return array($this->getMediaQueryEnd());
    }
    
    /**
     * Answers an array containing the prefix of the component using this rule.
     *
     * @return array
     */
    public function getPrefixes()
    {
        return array($this->Component()->getCSSID());
    }
    
    /**
     * Processes the given rule CSS array.
     *
     * @param array $css
     * @return array
     */
    protected function processRuleCSS($css = array())
    {
        return $this->indent($css);
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
