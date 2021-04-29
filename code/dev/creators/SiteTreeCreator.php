<?php

/**
 * An extension of the SilverWare creator object for creating site tree objects.
 */
class SiteTreeCreator extends SilverWareCreator
{
    /**
     * @config
     * @var string
     */
    private static $default_handler = "ChildHandler";
    
    /**
     * Answers either an existing instance or a new instance of the data object.
     *
     * @return DataObject
     */
    public function findOrCreate()
    {
        // Obtain Object:
        
        $object = $this->getExistingObject();
        
        // Need to Create?
        
        if (!$object) {
            
            // Create Object:
            
            $object = Injector::inst()->create($this->blueprint->getClass());
            
            // Populate Defaults:
            
            $this->populateDefaults($object);
            
            // Show Create Message:
            
            $this->createMessage($object);
            
        } else {
            
            // Show Update Message:
            
            $this->updateMessage($object);
            
        }
        
        // Handle Class Change:
        
        $this->mutateClass($object);
        
        // Update Identifier:
        
        $object->setIdentifier($this->getIdentifier());
        
        // Write Object to Default Stage:
        
        if ($object->isNew() || $object->isChanged(false, DataObject::CHANGE_VALUE)) {
            $object->writeToStage($object->getDefaultStage());
        }
        
        // Record Fixture ID:
        
        $this->blueprint->addFixtureID($object, $this->getIdentifier());
        
        // Answer Object:
        
        return $object;
    }
    
    /**
     * Answers the default filter for the receiver.
     *
     * @return array
     */
    public function getDefaultFilter()
    {
        $filter = parent::getDefaultFilter();
        
        if ($this->blueprint->hasData('ParentID')) {
            $filter['ParentID'] = $this->blueprint->getData('ParentID');
        }
        
        return $filter;
    }
    
    /**
     * Populates the given object from fixture data.
     *
     * @param DataObject $object
     * @return DataObject
     */
    public function populate(DataObject $object)
    {
        // Populate Object:
        
        parent::populate($object);
        
        // Publish Object to Other Stages:
        
        foreach ($object->getVersionedStages() as $stage) {
            
            if ($stage !== $object->getDefaultStage()) {
                $object->publish($object->getDefaultStage(), $stage);
            }
            
        }
        
        // Clear Object Cache:
        
        $object->flushCache();
        
        // Answer Object:
        
        return $object;
    }
}
