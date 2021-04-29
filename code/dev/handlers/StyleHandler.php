<?php

/**
 * An extension of the SilverWare handler class to handle styles.
 */
class StyleHandler extends SilverWareHandler
{
    /**
     * Handles the given blueprint name and data for the provided object.
     *
     * @param SilverWareCreator $creator
     * @param DataObject $object
     * @param string $name
     * @param array $data
     * @return boolean
     */
    public function handle(SilverWareCreator $creator, DataObject $object, $name, $data = null)
    {
        // Check Array Data:
        
        if (is_array($data)) {
            
            // Iterate Style Data:
            
            foreach ($data as $key => $value) {
                
                // Is key a device name?
                
                if ($this->grid()->isValidDeviceName($key) && is_array($value)) {
                    
                    // Create Device Rule:
                    
                    if ($rule = $this->createDeviceRule($creator, $object, $key, $value)) {
                        
                        // Populate Styles (for device rule):
                        
                        $this->populateStyles($creator, $rule, $value);
                        
                        // Remove Rule Data:
                        
                        unset($data[$key]);
                        
                    }
                    
                }
                
            }
            
            // Populate Styles (for object):
            
            $this->populateStyles($creator, $object, $data);
            
            // Answer Success:
            
            return true;
            
        }
        
        // Answer Failure:
        
        return false;
    }
    
    /**
     * Populates style properties for the given object using the given data.
     *
     * @param SilverWareCreator $creator
     * @param DataObject $object
     * @param array $data
     * @return DataObject
     */
    public function populateStyles(SilverWareCreator $creator, DataObject $object, $data)
    {
        // Check Array Data:
        
        if (is_array($data)) {
            
            // Obtain Blueprint:
            
            if ($blueprint = $creator->getFactory()->getBlueprint($object->class)) {
                
                // Define Blueprint:
                
                $blueprint->setData($data)->addPrefix('Style');
                
                // Obtain Creator and Populate Styles:
                
                if ($styleCreator = $blueprint->getCreator()) {
                    $styleCreator->populateData($object);
                    $styleCreator->populateRelations($object);
                }
                
            }
            
        }
    }
    
    /**
     * Creates a device rule for the provided object using the given data.
     *
     * @param SilverWareCreator $creator
     * @param DataObject $object
     * @param string $name
     * @param array $data
     * @return DeviceRule
     */
    public function createDeviceRule(SilverWareCreator $creator, DataObject $object, $name, $data)
    {
        if ($this->grid()->isValidDeviceName($name) && is_array($data)) {
            
            // Define Filters:
            
            $filters = array(
                'ComponentID' => $object->ID
            );
            
            // Define Data:
            
            if (!isset($data['ComponentID'])) {
                $data['ComponentID'] = $object->ID;
            }
            
            if (!isset($data['Device'])) {
                $data['Device'] = $this->getDeviceName($name);
            }
            
            // Define Identifier:
            
            $identifier = $this->getDeviceName($name);
            
            // Answer Rule Object:
            
            return $creator->getFactory()->createObject('DeviceRule', $identifier, $data, $filters);
            
        }
    }
    
    /**
     * Answers the correct name for the given device name.
     *
     * @param string $name
     * @return string
     */
    public function getDeviceName($name)
    {
        if ($this->grid()->isDefaultDeviceName($name)) {
            return $this->grid()->getDefaultDeviceName();
        }
        
        return $name;
    }
    
    /**
     * Answers the SilverWare grid singleton instance.
     *
     * @return SilverWareGrid
     */
    public function grid()
    {
        return SilverWareGrid::inst();
    }
}
