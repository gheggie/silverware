<?php

/**
 * An extension of the SilverWare handler class to handle child object creation for site tree objects.
 */
class ChildHandler extends SilverWareHandler
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
        // Check Object:
        
        if (!($object instanceof SiteTree)) {
            return false;
        }
        
        // Process Name and Class:
        
        $class = null;
        
        if ($creator->isTypedIdentifier($name)) {
            list($name, $class) = $creator->processTypedIdentifier($name);
        }
        
        // Check Name:
        
        if ($creator->hasHandler($name) || $creator->isRelation($object, $name) || $object->hasDatabaseField($name)) {
            return false;
        }
        
        // Check Data:
        
        if (is_array($data)) {
            
            // Obtain Blueprint:
            
            $blueprint = $creator->getBlueprint();
            
            // Define Parent ID:
            
            if (!isset($data['ParentID'])) {
                $data['ParentID'] = $object->ID;
            }
            
            // Define Sort Order:
            
            if (!isset($data['Sort']) && $blueprint->useAutoSort()) {
                $data['Sort'] = $blueprint->getSortAndIncrement();
            }
            
            // Obtain Child Class:
            
            if (!$class) {
                $class = $this->getChildClass($object, $data);
            }
            
            // Create Child Object:
            
            $child = $blueprint->getFactory()->createObject($class, $name, $data);
            
            // Answer Success:
            
            return true;
            
        }
        
        // Answer Failure:
        
        return false;
    }
    
    /**
     * Answers the appropriate child class for the given object.
     *
     * @param SiteTree $object
     * @param array $data
     * @return string
     */
    protected function getChildClass(SiteTree $object, $data)
    {
        return isset($data['ClassName']) ? $data['ClassName'] : $object->defaultChild();
    }
}
