<?php

/**
 * An extension of the fixture blueprint class for SilverWare objects.
 */
class SilverWareBlueprint extends FixtureBlueprint
{
    /**
     * Define constants.
     */
    const PATTERN_TYPED_IDENTIFIER  = "/^(\w+)\.(\w+)$|^(\w+)\[(\w+)\]$/";
    
    /**
     * @config
     * @var array
     */
    private static $dependencies = array(
        'factory' => '%$SilverWareFixtureFactory'
    );
    
    /**
     * @config
     * @var array
     */
    private static $creators = array();
    
    /**
     * @config
     * @var string
     */
    private static $default_creator = "SilverWareCreator";
    
    /**
     * @config
     * @var array
     */
    private static $default_parents;
    
    /**
     * @config
     * @var boolean
     */
    private static $auto_sort = true;
    
    /**
     * @config
     * @var boolean
     */
    private static $auto_site_config = true;
    
    /**
     * @var boolean
     */
    private static $validation_enabled;
    
    /**
     * @var boolean
     */
    private static $configured = false;
    
    /**
     * @var DataObject
     */
    protected $object;
    
    /**
     * @var SilverWareFixtureFactory
     */
    protected $factory;
    
    /**
     * @var string
     */
    protected $identifier;
    
    /**
     * @var array
     */
    protected $filters = array();
    
    /**
     * @var array
     */
    protected $data = array();
    
    /**
     * @var array
     */
    protected $done = array();
    
    /**
     * @var integer
     */
    protected $sort = 1;
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param string $name
     * @param string $class
     * @param array $defaults
     */
    public function __construct($name, $class = null, $defaults = array())
    {
        // Construct Parent:
        
        parent::__construct($name, $class, $defaults);
        
        // Construct Object:
        
        $this->init();
    }
    
    /**
     * Creates a data object using the fixture data.
     *
     * @param string $identifier
     * @param array $data
     * @param array $fixtures
     * @param array $filters
     * @return DataObject
     */
    public function createObject($identifier, $data = null, $fixtures = null, $filters = null)
    {
        // Define Properties:
        
        $this->setData($data);
        $this->setFilters($filters);
        $this->setIdentifier($identifier);
        
        // Trigger Before Event:
        
        $this->onBeforeCreate();
        
        // Obtain / Create Object:
        
        $this->setObject($this->getCreator()->createObject());
        
        // Trigger After Event:
        
        $this->onAfterCreate();
        
        // Answer Object:
        
        return $this->getObject();
    }
    
    /**
     * Answers a new creator instance for the blueprint class.
     *
     * @return SilverWareCreator
     */
    public function getCreator()
    {
        foreach ($this->getCreatorClasses() as $class => $creator) {
            
            if ($this->getClass() == $class || is_subclass_of($this->getClass(), $class)) {
                return Injector::inst()->create($creator, $this);
            }
            
        }
        
        return Injector::inst()->create($this->getDefaultCreatorClass(), $this);
    }
    
    /**
     * Answers an array of data object classes mapped to their creator classes from configuration.
     *
     * @return array
     */
    public function getCreatorClasses()
    {
        return Config::inst()->get(__CLASS__, 'creators');
    }
    
    /**
     * Answers the default creator class.
     *
     * @return string
     */
    public function getDefaultCreatorClass()
    {
        return Config::inst()->get(__CLASS__, 'default_creator');
    }
    
    /**
     * Adds the identifier string and record ID of the given object to the fixtures map.
     *
     * @param DataObject $object
     * @param string $identifier
     */
    public function addFixtureID(DataObject $object, $identifier)
    {
        return $this->factory->addFixtureID($object, $identifier);
    }
    
    /**
     * Defines the value of the factory attribute.
     *
     * @param SilverWareFixtureFactory $factory
     * @return SilverWareBlueprint
     */
    public function setFactory(SilverWareFixtureFactory $factory)
    {
        $this->factory = $factory;
        
        return $this;
    }
    
    /**
     * Defines the value of the object attribute.
     *
     * @param DataObject $object
     * @return SilverWareBlueprint
     */
    public function setObject(DataObject $object)
    {
        $this->object = $object;
        
        return $this;
    }
    
    /**
     * Answers the value of the object attribute.
     *
     * @return DataObject
     */
    public function getObject()
    {
        return $this->object;
    }
    
    /**
     * Answers the value of the factory attribute.
     *
     * @return SilverWareFixtureFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
    
    /**
     * Defines the value of the identifier attribute.
     *
     * @param string $identifier
     * @return SilverWareBlueprint
     */
    public function setIdentifier($identifier)
    {
        if ($this->isTypedIdentifier($identifier)) {
            list($identifier, $class) = $this->processTypedIdentifier($identifier);
            $this->addData('ClassName', $class);
        }
        
        $this->identifier = (string) $identifier;
        
        return $this;
    }
    
    /**
     * Answers the value of the identifier attribute.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    /**
     * Defines the value of the filters attribute.
     *
     * @param array $filters
     * @return SilverWareBlueprint
     */
    public function setFilters($filters)
    {
        $this->filters = (array) $filters;
        
        return $this;
    }
    
    /**
     * Answers the value of the filters attribute.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }
    
    /**
     * Answers true if filters exist for the receiver.
     *
     * @return boolean
     */
    public function hasFilters()
    {
        return !empty($this->filters);
    }
    
    /**
     * Defines the value of the sort attribute.
     *
     * @param integer $sort
     * @return SilverWareBlueprint
     */
    public function setSort($sort)
    {
        $this->sort = (integer) $sort;
        
        return $this;
    }
    
    /**
     * Answers the value of the sort attribute.
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }
    
    /**
     * Answers the value of the sort attribute and increments it.
     *
     * @return integer
     */
    public function getSortAndIncrement()
    {
        return $this->sort++;
    }
    
    /**
     * Answers true of the receiver uses auto sort mode.
     *
     * @return boolean
     */
    public function useAutoSort()
    {
        return Config::inst()->get(__CLASS__, 'auto_sort');
    }
    
    /**
     * Answers true of the receiver uses auto site config mode.
     *
     * @return boolean
     */
    public function useAutoSiteConfig()
    {
        return Config::inst()->get(__CLASS__, 'auto_site_config');
    }
    
    /**
     * Answers the default parent ID for the receiver.
     *
     * @return integer
     */
    public function getDefaultParentID()
    {
        return $this->getDefaultParentIDFor($this->getClass());
    }
    
    /**
     * Answers the default parent ID for the specified class.
     *
     * @param string $class
     * @return integer
     */
    public function getDefaultParentIDFor($class)
    {
        if ($default_parents = $this->getDefaultParents()) {
            
            if (isset($default_parents[$class])) {
                return $this->processValue($default_parents[$class]);
            }
            
        }
    }
    
    /**
     * Answers an array of default parent mappings.
     *
     * @return array
     */
    public function getDefaultParents()
    {
        if ($default_parents = Config::inst()->get(__CLASS__, 'default_parents')) {
            return $default_parents;
        }
        
        return array();
    }
    
    /**
     * Answers true of the receiver requires a default parent.
     *
     * @return boolean
     */
    public function useDefaultParent()
    {
        if ($default_parents = $this->getDefaultParents()) {
            return isset($default_parents[$this->getClass()]);
        }
    }
    
    /**
     * Defines the value of the data attribute.
     *
     * @param array $data
     * @return SilverWareBlueprint
     */
    public function setData($data)
    {
        $this->data = (array) $data;
        
        return $this;
    }
    
    /**
     * Answers the value of the data attribute, or the value associated with the given name.
     *
     * @param string $name (optional)
     * @return mixed
     */
    public function getData($name = null)
    {
        if (!is_null($name)) {
            return $this->hasData($name) ? $this->data[$name] : null;
        }
        
        return $this->data;
    }
    
    /**
     * Adds the given name and value to the data attribute.
     *
     * @param string $name
     * @param mixed $value
     * @return SilverWareBlueprint
     */
    public function addData($name, $value = null)
    {
        $this->data[$name] = $value;
        
        return $this;
    }
    
    /**
     * Answers true if data exists with the specified name.
     *
     * @param string $name
     * @return boolean
     */
    public function hasData($name)
    {
        return isset($this->data[$name]);
    }
    
    /**
     * Remove the data associated with the specified name.
     *
     * @param string $name
     * @return SilverWareBlueprint
     */
    public function removeData($name)
    {
        if ($this->hasData($name)) {
            unset($this->data[$name]);
        }
        
        return $this;
    }
    
    /**
     * Moves data with the given name from the data property to the done property after it has been handled.
     *
     * @param string $name
     */
    public function handled($name)
    {
        if ($this->hasData($name)) {
            $this->done[$name] = $this->getData($name);
            $this->removeData($name);
        }
    }
    
    /**
     * Adds the given prefix to the keys within the data array.
     *
     * @param string $prefix
     * @return SilverWareBlueprint
     */
    public function addPrefix($prefix)
    {
        foreach ($this->data as $key => $value) {
            $this->data[sprintf('%s%s', $prefix, $key)] = $value;
            unset($this->data[$key]);
        }
        
        return $this;
    }
    
    /**
     * Adds the given suffix to the keys within the data array.
     *
     * @param string $suffix
     * @return SilverWareBlueprint
     */
    public function addSuffix($suffix)
    {
        foreach ($this->data as $key => $value) {
            $this->data[sprintf('%s%s', $key, $suffix)] = $value;
            unset($this->data[$key]);
        }
        
        return $this;
    }
    
    /**
     * Processes the given value and answers the resulting data.
     *
     * @param string $value
     * @return mixed
     */
    public function processValue($value)
    {
        if (is_array($value)) {
            
            return implode(',', $value);
            
        } else {
            
            $value = trim($value);
            
            if ($this->isCode($value)) {
                
                return $this->processCode($value);
                
            } elseif ($this->isReference($value)) {
                
                // Process Reference String:
                
                list($class, $identifier) = $this->processReference($value);
                
                // Obtain Fixture Definitions:
                
                if ($fixtures = $this->factory->getFixtures()) {
                    
                    if (!isset($fixtures[$class][$identifier])) {
                        
                        throw new InvalidArgumentException(
                            sprintf(
                                "No fixture definitions found for '%s'",
                                $value
                            )
                        );
                        
                    }
                    
                    // Answer Fixture ID:
                    
                    return $fixtures[$class][$identifier];
                    
                }
                
            }
            
            // Answer Regular String:
            
            return $value;
            
        }
    }
    
    /**
     * Processes the given value as a file and answers the object ID.
     *
     * @param string $value
     * @param string $class
     * @param string $folder
     * @return integer
     */
    public function processFile($value, $class = 'File', $folder = null)
    {
        // Obtain Source Path:
        
        $source = Director::getAbsFile($value);
        
        // Does Source Exist?
        
        if (file_exists($source)) {
            
            // Obtain Source File Name:
            
            $name = basename($source);
            
            // Obtain Target Folder Name:
            
            $dir = $folder ? $folder : Config::inst()->get('Upload', 'uploads_folder');
            
            // Define Asset File Name:
            
            $filename = sprintf('%s/%s/%s', ASSETS_DIR, $dir, $name);
            
            // Define Target Path:
            
            $target = Director::getAbsFile($filename);
            
            // Does File Object Exist?
            
            if (!($file = File::find($filename))) {
                
                // Find or Create Parent Folder:
                
                $parent = Folder::find_or_make($dir);
                
                // Copy Source to Target:
                
                if (copy($source, $target)) {
                    
                    // Create File Object:
                    
                    $file = $class::create();
                    
                    // Define File Object:
                    
                    $file->setName($name);
                    $file->setFilename($filename);
                    $file->setParentID($parent->ID);
                    
                    // Record File Object:
                    
                    $file->write();
                    
                }
                
            }
            
            // Answer File Object ID:
            
            return $file->ID;
            
        }
        
        // Answer Zero (on failure):
        
        return 0;
    }
    
    /**
     * Answers true if the specified class is a file object.
     *
     * @param string $class
     * @return boolean
     */
    public function isFile($class)
    {
        return in_array($class, ClassInfo::subclassesFor('File'));
    }
    
    /**
     * Answers true if the given value is a code string.
     *
     * @param string $value
     * @return boolean
     */
    public function isCode($value)
    {
        if (!is_array($value)) {
            return preg_match('/^`(.)*`$/', $value);
        }
        
        return false;
    }
    
    /**
     * Processes the given code string and answers the evaluated result.
     *
     * @param string $value e.g. `SiteConfig::current_site_config()->ID`
     * @return mixed
     */
    public function processCode($value)
    {
        if ($this->isCode($value)) {
            
            // Process Code String:
            
            $code = trim($value, '`');
            
            // Answer Evaluated Result:
            
            return eval("return $code;");
            
        }
    }
    
    /**
     * Answers true if the given value is a fixture reference string.
     *
     * @param string $value
     * @return boolean
     */
    public function isReference($value)
    {
        if (!is_array($value)) {
            return (substr($value, 0, 2) == '=>');
        }
        
        return false;
    }
    
    /**
     * Processes the given reference string and returns an array containing the class and identifier.
     *
     * @param string $value
     * @param array $associative
     * @return array
     */
    public function processReference($value, $associative = false)
    {
        if ($this->isReference($value)) {
            
            $ref = explode('.', substr($value, 2), 2);
            
            return $associative ? array('class' => $ref[0], 'identifier' => $ref[1]) : $ref;
            
        }
    }
    
    /**
     * Answers true if the given identifier string is a 'typed' identifier (with class name).
     *
     * @param string $identifier
     * @return boolean
     */
    public function isTypedIdentifier($identifier)
    {
        return preg_match(self::PATTERN_TYPED_IDENTIFIER, $identifier);
    }
    
    /**
     * Processes the given typed identifier and answers an array containing the true identifier and the class name.
     *
     * @param string $identifier
     * @return array
     */
    public function processTypedIdentifier($identifier)
    {
        if (preg_match(self::PATTERN_TYPED_IDENTIFIER, $identifier, $matches)) {
            
            if (isset($matches[3]) && isset($matches[4])) {
                return array($matches[3], $matches[4]);  // period pattern match, i.e. Name.Class
            } else {
                return array($matches[1], $matches[2]);  // bracket pattern match, i.e. Name[Class]
            }
            
        }
    }
    
    /**
     * Performs initialisation on the receiver.
     */
    public function init()
    {
        // Configure Statics:
        
        if (!self::$configured) {
            
            self::$validation_enabled = Config::inst()->get('DataObject', 'validation_enabled');
            
            self::$configured = true;
            
        }
        
        // Add Callback for 'onBeforeCreate' Event Handling:
        
        $this->addCallback('beforeCreate', function($self) {
            
            singleton($self->getClass())->onBeforeCreate($self);
            
        });
        
        // Add Callback for Default Parents:
        
        $this->addCallback('beforeCreate', function($self) {
            
            if ($self->useDefaultParent() && !$self->hasData('ParentID')) {
                
                if ($ParentID = $self->getDefaultParentID()) {
                    $self->addData('ParentID', $ParentID);
                }
                
            }
            
        });
        
        // Add Callback for Code Evaluation:
        
        $this->addCallback('beforeCreate', function($self) {
            
            foreach ($self->getData() as $key => $value) {
                
                if ($self->isCode($value)) {
                    
                    // Replace Data with Evaluated Result:
                    
                    $self->addData($key, $self->processCode($value));
                    
                }
                
            }
            
        });
        
        // Add Callback for Auto Site Config:
        
        $this->addCallback('afterCreate', function($self) {
            
            if ($self->useAutoSiteConfig()) {
                
                $object = $self->getObject();
                
                foreach ($object->hasOne() as $field => $class) {
                    
                    if ($class == 'SiteConfig') {
                        $object->{$field . 'ID'} = SiteConfig::current_site_config()->ID;
                    }
                    
                }
                
                if ($object->isChanged(false, DataObject::CHANGE_VALUE)) {
                    $object->write();
                }
                
            }
            
        });
        
        // Add Callback for Handling Versioning:
        
        $this->addCallback('afterCreate', function($self) {
            
            // Handle Versioned Objects:
            
            $object = $self->getObject();
            
            if ($object->hasExtension('Versioned')) {
                
                // Detect Object Change:
                
                if ($object->isChanged(false, DataObject::CHANGE_VALUE)) {
                    
                    // Write Object to Default Stage:
                    
                    $object->writeToStage($object->getDefaultStage());
                    
                    // Publish Object to Other Stages:
                    
                    foreach ($object->getVersionedStages() as $stage) {
                        
                        if ($stage !== $object->getDefaultStage()) {
                            $object->publish($object->getDefaultStage(), $stage);
                        }
                        
                    }
                    
                    // Clear Object Cache:
                    
                    $object->flushCache();
                    
                }
                
            }
            
        });
        
        // Add Callback for 'onAfterCreate' Event Handling:
        
        $this->addCallback('afterCreate', function($self) {
            
            $self->getObject()->onAfterCreate($self);
            
        });
    }
    
    /**
     * Disables data object validation while importing fixtures.
     */
    protected function disableValidation()
    {
        Config::inst()->update('DataObject', 'validation_enabled', false);
    }
    
    /**
     * Enables data object validation after importing fixtures.
     */
    protected function enableValidation()
    {
        Config::inst()->update('DataObject', 'validation_enabled', self::$validation_enabled);
    }
    
    /**
     * Event method called before the object is created.
     */
    protected function onBeforeCreate()
    {
        $this->disableValidation();
        
        $this->invokeCallbacks('beforeCreate', array($this));
    }
    
    /**
     * Event method called after the object is created.
     *
     * @param DataObject $object
     */
    protected function onAfterCreate()
    {
        $this->invokeCallbacks('afterCreate', array($this));
        
        $this->enableValidation();
    }
}
