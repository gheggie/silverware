<?php

/**
 * An extension of the SilverWare handler class to handle site config settings.
 */
class ConfigHandler extends SilverWareHandler
{
    /**
     * @var string
     */
    protected $name = "SilverWare";
    
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
            
            // Obtain Blueprint:
            
            if ($blueprint = $creator->getFactory()->getBlueprint($object->class)) {
                
                // Define Config Data:
                
                $configData = array();
                
                foreach ($data as $k => $v) {
                    $configData["SilverWare{$k}"] = $v;
                }
                
                // Define Blueprint:
                
                $blueprint->setData($configData);
                
                // Obtain Creator and Populate Config:
                
                if ($configCreator = $blueprint->getCreator()) {
                    $configCreator->populateData($object);
                    $configCreator->populateRelations($object);
                }
                
            }
            
            // Answer Success:
            
            return true;
            
        }
        
        // Answer Failure:
        
        return false;
    }
}
