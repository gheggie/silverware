<?php

/**
 * An extension of the SilverWare creator class for site config.
 */
class SiteConfigCreator extends SilverWareCreator
{
    /**
     * Answers an existing object matching the blueprint criteria.
     *
     * @return DataObject
     */
    public function getExistingObject()
    {
        // Answer Existing Fixture Object (used for SiteConfig.Current):
        
        if ($this->getFactory()->hasFixture($this->blueprint->getClass(), $this->getIdentifier())) {
            return $this->getFactory()->getFixtureObject($this->blueprint->getClass(), $this->getIdentifier());
        }
        
        // Answer Existing Object (via parent method):
        
        return parent::getExistingObject();
    }
}
