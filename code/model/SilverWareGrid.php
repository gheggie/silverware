<?php

/**
 * An extension of the object class for the SilverWare grid object.
 */
class SilverWareGrid extends Object
{
    /**
     * @config
     * @var array
     */
    private static $devices = array();
    
    /**
     * @config
     * @var array
     */
    private static $device_groups = array();
    
    /**
     * @config
     * @var string
     */
    private static $default_device_key = "any";
    
    /**
     * @config
     * @var string
     */
    private static $default_device_name = "Any";
    
    /**
     * @config
     * @var array
     */
    private static $default_device_aliases = array(
        'any',
        'all',
        'default'
    );
    
    /**
     * @var SilverWareGrid
     */
    private static $instance;
    
    /**
     * Answers the singleton grid instance.
     *
     * @return SilverWareGrid
     */
    public static function inst()
    {
        if (!self::$instance) {
            self::$instance = SilverWareGrid::create();
        }
        
        return self::$instance;
    }
    
    /**
     * Answers an array containing the devices from configuration.
     *
     * @return array
     */
    public function getDevices()
    {
        return $this->config()->devices;
    }
    
    /**
     * Answers an array containing the device groups from configuration.
     *
     * @return array
     */
    public function getDeviceGroups()
    {
        return $this->config()->device_groups;
    }
    
    /**
     * Answers the key of the default device.
     *
     * @return string
     */
    public function getDefaultDeviceKey()
    {
        return $this->config()->default_device_key;
    }
    
    /**
     * Answers the name of the default device.
     *
     * @return string
     */
    public function getDefaultDeviceName()
    {
        return $this->config()->default_device_name;
    }
    
    /**
     * Answers an array of default device aliases.
     *
     * @return array
     */
    public function getDefaultDeviceAliases()
    {
        return $this->config()->default_device_aliases;
    }
    
    /**
     * Answers an array containing the display grid from configuration.
     *
     * @return array
     */
    public function getDisplayGrid()
    {
        $grid = array();
        
        foreach ($this->getDevices() as $device => $spec) {
            
            if (isset($spec['width'])) {
                $grid[$this->clean($device)] = (integer) $spec['width'];
            }
            
        }
        
        return $grid;
    }
    
    /**
     * Answers an array containing the devices from configuration mapped to their names.
     *
     * @return array
     */
    public function getDeviceMap()
    {
        $devices = array();
        
        foreach ($this->getDevices() as $device => $spec) {
            
            if (isset($spec['name'])) {
                $devices[$this->clean($device)] = $spec['name'];
            }
            
        }
        
        return $devices;
    }
    
    /**
     * Answers true if the given string is a valid device name.
     * 
     * @param string $device
     * @return string
     */
    public function isDeviceName($device)
    {
        if ($devices = $this->getDeviceMap()) {
            return isset($devices[$this->clean($device)]);
        }
        
        return false;
    }
    
    /**
     * Answers true if the given string is a default device name.
     * 
     * @param string $device
     * @return boolean
     */
    public function isDefaultDeviceName($device)
    {
        return in_array($this->clean($device), $this->getDefaultDeviceAliases());
    }
    
    /**
     * Answers true if the given string is a valid device name.
     * 
     * @param string $device
     * @return boolean
     */
    public function isValidDeviceName($device)
    {
        return ($this->isDeviceName($device) || $this->isDefaultDeviceName($device));
    }
    
    /**
     * Answers the name for the specified device.
     * 
     * @param string $device
     * @return string
     */
    public function getDeviceName($device)
    {
        if ($devices = $this->getDeviceMap()) {
            
            $device = $this->clean($device);
            
            if (isset($devices[$device])) {
                return $devices[$device];
            }
            
        }
    }
    
    /**
     * Answers an array of device names in the specified group.
     * 
     * @param string $name
     * @param boolean $includeDefault
     * @return array
     */
    public function getDeviceGroup($name, $includeDefault = true)
    {
        $group = array();
        
        if ($groups = $this->getDeviceGroups()) {
            
            $name = $this->clean($name);
            
            if (isset($groups[$name]) && is_array($groups[$name])) {
                
                $group = $groups[$name];
                
                if ($includeDefault && !in_array($this->getDefaultDeviceKey(), $group)) {
                    array_unshift($group, $this->getDefaultDeviceKey());
                }
                
            }
            
        }
        
        return $group;
    }
    
    /**
     * Answers an array containing a list of device options and their widths from configuration.
     *
     * @return array
     */
    public function getDeviceOptions()
    {
        $options = array();
        
        foreach ($this->getDevices() as $device => $spec) {
            
            if (isset($spec['name']) && isset($spec['name'])) {
                
                if ($spec['width']) {
                    
                    $options[$this->clean($device)] = sprintf(
                        '%s (%d %s)',
                        $spec['name'],
                        $spec['width'],
                        _t('SilverWareGrid.PIXELSWIDE', 'pixels wide')
                    );
                    
                } else {
                    
                    $options[$this->clean($device)] = $spec['name'];
                    
                }
                
            }
            
        }
        
        return $options;
    }
    
    /**
     * Answers the minimum width for the specified device.
     *
     * @param string $device
     * @return integer
     */
    public function getDeviceMinWidth($device)
    {
        if ($grid = $this->getDisplayGrid()) {
            
            $device = $this->clean($device);
            
            if (isset($grid[$device])) {
                return $grid[$device];
            }
            
        }
        
        return 0;
    }
    
    
    /**
     * Sanitises the given device name string.
     *
     * @param string $device
     * @return string
     */
    public function clean($device)
    {
        return preg_replace( // Convert Whitespace to Dashes
            '/[\s_]/',
            '-',
            preg_replace(    // Replace Multiple Dashes/Whitespace
                '/[\s-]+/',
                ' ',
                strtolower(  // Trim, Convert to Lowercase
                    trim(
                        $device
                    )
                )
            )
        );
    }
}
