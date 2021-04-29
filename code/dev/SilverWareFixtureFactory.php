<?php

/**
 * An extension of the fixture factory class for building SilverWare objects from fixtures.
 */
class SilverWareFixtureFactory extends FixtureFactory
{
    /**
     * Constructs the object upon instantiation (note: parent class currently has no constructor to call).
     */
    public function __construct()
    {
        // Construct Object:
        
        $this->addFixtureID(SiteConfig::current_site_config(), 'Current');  // shortcut reference for SiteConfig
    }
    
    /**
     * Writes the fixture into the database using data objects.
     *
     * @param string $class (subclass of DataObject)
     * @param string $identifier
     * @param array $data
     * @param array $filters
     * @return DataObject
     */
    public function createObject($class, $identifier, $data = null, $filters = null)
    {
        // Check Class Name:
        
        $this->checkClass($class);
        
        // Obtain Class Blueprint:
        
        if ($blueprint = $this->getBlueprint($class)) {
            
            // Create Object:
            
            $object = $blueprint->createObject($identifier, $data, $this->fixtures, $filters);
            
        }
        
        // Answer Object:
        
        return $object;
    }
    
    /**
     * Adds the identifier string and record ID of the given object to the fixtures map.
     *
     * @param DataObject $object
     * @param string $identifier
     */
    public function addFixtureID(DataObject $object, $identifier)
    {
        if (!isset($this->fixtures[$object->class])) {
            $this->fixtures[$object->class] = array();
        }
        
        $this->fixtures[$object->class][$identifier] = $object->ID;
    }
    
    /**
     * Answers the ID for the fixture with the given class and identifier.
     *
     * @param string $class
     * @param string $identifier
     * @return integer
     */
    public function getFixtureID($class, $identifier)
    {
        if ($this->hasFixture($class, $identifier)) {
            return $this->fixtures[$class][$identifier];
        }
    }
    
    /**
     * Answers true if a fixture exists with the given class and identifier.
     *
     * @param string $class
     * @param string $identifier
     * @return boolean
     */
    public function hasFixture($class, $identifier)
    {
        return isset($this->fixtures[$class][$identifier]);
    }
    
    /**
     * Answers an object identified by the given fixture class and identifier.
     *
     * @param string $class
     * @param string $identifier
     * @return DataObject
     */
    public function getFixtureObject($class, $identifier)
    {
        if ($ID = $this->getFixtureID($class, $identifier)) {
            return $class::get()->byID($ID);
        }
    }
    
    /**
     * Answers the array of configured blueprint instances.
     *
     * @return array
     */
    public function getBlueprints()
    {
        $blueprints = $this->blueprints;
        
        if ($config = SilverWare::config()->get('blueprints')) {
            
            if (ArrayLib::is_associative($config)) {
                
                foreach ($config as $class => $blueprint) {
                    $blueprints[$class] = is_array($blueprint) ? $blueprint : array('class' => $blueprint);
                }
                
            }
            
        }
        
        return $blueprints;
    }
    
    /**
     * Answers a new blueprint instance with the specified name.
     *
     * @param string $name
     * @return FixtureBlueprint
     */
    public function getBlueprint($name)
    {
        if ($blueprints = $this->getBlueprints()) {
            
            if (isset($blueprints[$name])) {
                
                if (is_object($blueprints[$name])) {
                    
                    return $blueprints[$name];
                
                } elseif (is_array($blueprints[$name]) && isset($blueprints[$name]['class'])) {
                    
                    $defaults = isset($blueprints[$name]['defaults']) ? $blueprints[$name]['defaults'] : array();
                    
                    return $this->getBlueprintInstance(
                        $blueprints[$name]['class'],
                        $name,
                        $defaults
                    );
                    
                }
                
            }
            
        }
        
        return $this->getDefaultBlueprint($name);
    }
    
    /**
     * Answers a new instance of the default blueprint class with the given name.
     *
     * @param string $name
     * @return FixtureBlueprint
     */
    public function getDefaultBlueprint($name)
    {
        return $this->getBlueprintInstance(SilverWare::config()->get('default_blueprint'), $name);
    }
    
    /**
     * Answers a new blueprint instance of the specified class with the given name.
     *
     * @param string $class
     * @param string $name
     * @param array $defaults
     * @return FixtureBlueprint
     */
    public function getBlueprintInstance($class, $name, $defaults = array())
    {
        $this->checkBlueprintClass($class);
        
        return Injector::inst()->create($class, $name)->setFactory($this)->setDefaults($defaults);
    }
    
    /**
     * Checks the validity of the given blueprint class.
     *
     * @param string $class
     *
     * @throws Exception
     */
    public function checkBlueprintClass($class)
    {
        if ($class != 'FixtureBlueprint' && !is_subclass_of($class, 'FixtureBlueprint')) {
            throw new Exception(sprintf('%s is not a FixtureBlueprint', $class));
        }
    }
    
    /**
     * Checks the validity of the given class.
     *
     * @param string $class
     *
     * @throws Exception
     */
    protected function checkClass($class)
    {
        if (!is_subclass_of($class, 'DataObject')) {
            throw new Exception(sprintf('%s is not an subclass of DataObject', $class));
        }
    }
}
