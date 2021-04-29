<?php

/**
 * An extension of the data object class for a linked style.
 */
class LinkedStyle extends DataObject
{
    private static $singular_name = "Linked Style";
    private static $plural_name   = "Linked Styles";
    
    private static $db = array(
        'Name' => 'Varchar(128)'
    );
    
    private static $has_one = array(
        'Style' => 'CustomStyle',
        'LinkedTo' => 'SilverWareComponent'
    );
    
    /**
     * Answers the prefix for the component linked to this style.
     *
     * @return string
     */
    public function getPrefix()
    {
        if ($this->LinkedToID) {
            return $this->LinkedTo()->getCustomStylePrefix($this->Name);
        }
    }
    
    /**
     * Answers the mappings for the component linked to this style.
     *
     * @return array
     */
    public function getMappings()
    {
        if ($this->LinkedToID) {
            return $this->LinkedTo()->getCustomStyleMappings($this->Name);
        }
    }
    
    /**
     * Answers the mapped attribute with the given name.
     *
     * @param string $name
     * @return string
     */
    public function getMappedAttribute($name)
    {
        $name = ltrim($name, '$');
        
        if ($mappings = $this->getMappings()) {
            
            if (isset($mappings[$name])) {
                return $mappings[$name];
            }
            
        }
        
        return $name;
    }
}
