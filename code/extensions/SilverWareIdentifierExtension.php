<?php

/**
 * An extension of the data extension class to add identifier fields to data objects.
 */
class SilverWareIdentifierExtension extends DataExtension
{
    private static $db = array(
        'Identifier' => 'Varchar(255)'
    );
    
    /**
     * @config
     * @var array
     */
    private static $core_classes = array(
        'SiteTree'
    );
    
    /**
     * Applies the extension to the extendable classes.
     */
    public static function extend()
    {
        foreach (self::extendable_classes() as $class) {
            $class::add_extension(__CLASS__);
        }
    }
    
    /**
     * Answers an array of the classes which are extendable by this extension.
     *
     * @return array
     */
    public static function extendable_classes()
    {
        // Create Array:
        
        $extendable = array();
        
        // Iterate Data Object Subclasses:
        
        foreach (ClassInfo::dataClassesFor('DataObject') as $class) {
            
            $base = ClassInfo::baseDataClass($class);
            
            if (!isset($extendable[$base]) && self::is_extendable_class($base)) {
                $extendable[$base] = $base;
            }
            
        }
        
        // Answer Array:
        
        return $extendable;
    }
    
    /**
     * Answers true if the specified class is an extendable class (non-core data object, or permitted core classes).
     *
     * @param string $class
     * @return boolean
     */
    public static function is_extendable_class($class)
    {
        $permitted = Config::inst()->get(__CLASS__, 'core_classes');
        
        return (in_array($class, $permitted) || !self::is_core_class($class));
    }
    
    /**
     * Answers true if the specified class is a 'core' class, i.e. within the cms or framework folders.
     *
     * @param string $class
     * @return boolean
     */
    public static function is_core_class($class)
    {
        $path = SS_ClassLoader::instance()->getItemPath($class);
        
        return (strpos($path, FRAMEWORK_PATH) !== false || strpos($path, CMS_PATH) !== false);
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Define Identifier:
        
        if (!$this->owner->Identifier) {
            
            if ($this->owner instanceof SiteTree || $this->owner->hasExtension('URLSegmentExtension')) {
                
                // Use URL Segment:
                
                $this->owner->Identifier = self::clean($this->owner->URLSegment);
                
            } else {
                
                // Fallback to Title:
                
                $this->owner->Identifier = self::clean($this->owner->Title);
                
            }
            
        }
    }
    
    /**
     * Sanitises the given identifier using a URL segment filter.
     *
     * @param string $identifier
     * @return string
     */
    public static function clean($identifier)
    {
        return URLSegmentFilter::create()->filter(self::format($identifier));
    }
    
    /**
     * Formats the given identifier string using FormField name to label.
     *
     * @uses FormField::name_to_label()
     *
     * @param string $identifier
     * @return string
     */
    public static function format($identifier)
    {
        return FormField::name_to_label($identifier);
    }
    
    /**
     * Defines the identifier for the extended object.
     *
     * @param string $identifier
     * @return DataObject
     */
    public function setIdentifier($identifier)
    {
        // Update Identifier Field:
        
        $this->owner->setField('Identifier', self::clean($identifier));
        
        // Answer Extended Object:
        
        return $this->owner;
    }
    
    /**
     * Defines the title of the extended object from the given identifier (used when populating defaults).
     *
     * @param string $identifier
     * @return DataObject
     */
    public function setTitleFromIdentifier($identifier)
    {
        // Define Title (if field exists):
        
        if ($this->owner->hasDatabaseField('Title')) {
            return $this->owner->setField('Title', self::format($identifier));
        }
        
        // Define Name (if field exists):
        
        if ($this->owner->hasDatabaseField('Name')) {
            return $this->owner->setField('Name', self::format($identifier));
        }
        
        // Answer Extended Object:
        
        return $this->owner;
    }
    
    /**
     * Event method called before an instance of the extended object is created (via singleton).
     *
     * @param SilverWareBlueprint $blueprint
     */
    public function onBeforeCreate(SilverWareBlueprint $blueprint)
    {
        
    }
    
    /**
     * Event method called after an instance of the extended object is created.
     *
     * @param SilverWareBlueprint $blueprint
     */
    public function onAfterCreate(SilverWareBlueprint $blueprint)
    {
        
    }
    
    /**
     * Answers the identifier mapping configuration from the extended object.
     *
     * @return array
     */
    public function getIdentifierMappings()
    {
        $mappings = array();
        
        if (is_array($this->owner->config()->identifier_mappings)) {
            return $this->owner->config()->identifier_mappings;
        }
        
        return $mappings;
    }
    
    /**
     * Answers the identifier mapping for the specified field and identifier string.
     *
     * @param string $field
     * @param string $identifier
     * @return array
     */
    public function getIdentifierMapping($field, $identifier)
    {
        if ($mappings = $this->owner->getIdentifierMappingsForField($field)) {
            
            foreach ($mappings as $name => $mapping) {
                
                if (strtolower($name) == strtolower($this->getIdentifierName($identifier))) {
                    
                    if (!is_array($mapping)) {
                        return array('class' => $mapping);
                    }
                    
                    return $mapping;
                    
                }
                
            }
            
        }
    }
    
    /**
     * Answers the identifier mappings for the specified field.
     *
     * @param string $field
     * @return array
     */
    public function getIdentifierMappingsForField($field)
    {
        $mappings = $this->owner->getIdentifierMappings();
        
        if (isset($mappings[$field])) {
            return $mappings[$field];
        }
    }
    
    /**
     * Answers true if the given identifier is a mapping for the specified field on the extended object.
     *
     * @param string $field
     * @param string $identifier
     * @return boolean
     */
    public function isIdentifierMapping($field, $identifier)
    {
        return (boolean) $this->owner->getIdentifierMapping($field, $identifier);
    }
    
    /**
     * Answers an array containing the processed result of mapping the specified field and identifier.
     *
     * @param string $field
     * @param string $identifier
     * @return array
     */
    public function processIdentifierMapping($field, $identifier)
    {
        if ($mapping = $this->owner->getIdentifierMapping($field, $identifier)) {
            
            $params = array();
            
            if (isset($mapping['params']) && is_array($mapping['params'])) {
                
                preg_match_all('/{(.*?)}/', $identifier, $matches);
                
                if (isset($matches[1])) {
                    
                    $params = array_combine(
                        $mapping['params'],
                        array_pad($matches[1], count($mapping['params']), null)
                    );
                    
                }
                
            }
            
            return array(
                $this->getIdentifierName($identifier),
                $mapping['class'],
                $params
            );
            
        }
    }
    
    /**
     * Extracts the name from the given identifier (removing any params).
     *
     * @param string $identifier
     * @return string
     */
    private function getIdentifierName($identifier)
    {
        $i = strpos($identifier, '{');
        
        if ($i !== false) {
            $identifier = substr($identifier, 0, $i);
        }
        
        return $identifier;
    }
}
