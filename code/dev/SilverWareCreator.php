<?php

/**
 * An extension of the object class for a SilverWare creator object.
 */
class SilverWareCreator extends Object
{
    /**
     * @config
     * @var array
     */
    private static $handlers = array();
    
    /**
     * @config
     * @var string
     */
    private static $default_handler;
    
    /**
     * @var array
     */
    protected static $handler_objects = array();
    
    /**
     * @var array
     */
    protected static $handler_default = array();
    
    /**
     * @var array
     */
    protected static $configured = array();
    
    /**
     * @var SilverWareBlueprint
     */
    protected $blueprint;
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param SilverWareBlueprint $blueprint
     */
    public function __construct(SilverWareBlueprint $blueprint)
    {
        // Construct Parent:
        
        parent::__construct();
        
        // Construct Object:
        
        $this->setBlueprint($blueprint);
        
        // Initialise:
        
        $this->init();
    }
    
    /**
     * Performs initialisation on the receiver.
     */
    public function init()
    {
        // Configure Handlers:
        
        if (!isset(self::$configured[$this->class])) {
            
            // Configure Defined Handlers:
            
            if ($classes = Config::inst()->get($this->class, 'handlers')) {
                
                foreach ($classes as $class) {
                    
                    if ($handler = Injector::inst()->create($class)) {
                        self::$handler_objects[$handler->getName()] = $handler;
                    }
                    
                }
                
            }
            
            // Configure Default Handler:
            
            if ($class = Config::inst()->get($this->class, 'default_handler')) {
                self::$handler_default[$this->class] = Injector::inst()->create($class);
            }
            
            // Flag as Configured:
            
            self::$configured[$this->class] = true;
            
        }
        
    }
    
    /**
     * Defines the value of the blueprint attribute.
     *
     * @param SilverWareBlueprint $blueprint
     * @return SilverWareCreator
     */
    public function setBlueprint(SilverWareBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
        
        return $this;
    }
    
    /**
     * Answers the value of the blueprint attribute.
     *
     * @return SilverWareBlueprint
     */
    public function getBlueprint()
    {
        return $this->blueprint;
    }
    
    /**
     * Answers the fixture factory from the associated blueprint object.
     *
     * @return SilverWareFixtureFactory
     */
    public function getFactory()
    {
        return $this->blueprint->getFactory();
    }
    
    /**
     * Answers the object identifier from the associated blueprint object.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->blueprint->getIdentifier();
    }
    
    /**
     * Creates or obtains an object for a blueprint.
     *
     * @return DataObject
     */
    public function createObject()
    {
        // Create / Obtain Object:
        
        $object = $this->findOrCreate();
        
        // Define Object via Handlers:
        
        foreach ($this->blueprint->getData() as $name => $data) {
            $this->handle($object, $name, $data);
        }
        
        // Populate Object with Data:
        
        $this->populate($object);
        
        // Answer Object:
        
        return $object;
    }
    
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
        
        // Write Object to Database:
        
        $object->write();
        
        // Record Fixture ID:
        
        $this->blueprint->addFixtureID($object, $this->getIdentifier());
        
        // Answer Object:
        
        return $object;
    }
    
    /**
     * Answers an existing object matching the blueprint criteria.
     *
     * @return DataObject
     */
    public function getExistingObject()
    {
        $filter = $this->getExistingFilter();
        
        if ($matches = DataList::create($this->blueprint->getClass())->filter($filter)) {
            
            if ($matches->count() > 0) {
                return $matches->first();
            }
            
        }
    }
    
    /**
     * Answers a filter array used to find an existing data object matching the criteria.
     *
     * @return array
     */
    public function getExistingFilter()
    {
        // Create Filter Array:
        
        $filter = array();
        
        // Merge Filter Parameters:
        
        if ($this->blueprint->hasFilters()) {
            $filter = array_merge($filter, $this->blueprint->getFilters());
        }
        
        // Define Filter Criteria:
        
        if ($this->blueprint->hasData('MatchOn')) {
            
            // Define Custom Filtering:
            
            foreach ($this->blueprint->getData('MatchOn') as $field) {
                
                if ($this->blueprint->hasData($field)) {
                    $filter[$field] = $this->blueprint->getData($field);
                } elseif ($field == 'ClassName') {
                    $filter[$field] = $this->blueprint->getClass();
                }
                
            }
            
            // Remove Custom Filter Data:
            
            $this->blueprint->handled('MatchOn');
            
        } else {
            
            // Merge Default Filter:
            
            $filter = array_merge($filter, $this->getDefaultFilter());
            
        }
        
        // Answer Filter Array:
        
        return $filter;
    }
    
    /**
     * Answers the default filter for the receiver.
     *
     * @return array
     */
    public function getDefaultFilter()
    {
        return array(
            'Identifier' => $this->cleanIdentifier($this->getIdentifier())
        );
    }
    
    /**
     * Uses a handler instance to define the object using the given name and data.
     *
     * @param DataObject $object
     * @param string $name
     * @param array $data
     */
    public function handle(DataObject $object, $name, $data = null)
    {
        // Obtain Handler:
        
        if ($handler = $this->getHandler($name)) {
            
            // Delegate to Handler:
            
            if ($handler->handle($this, $object, $name, $data)) {
                $this->blueprint->handled($name);
            }
            
        }
    }
    
    /**
     * Answers the handler with the specified name (or the default handler if not found).
     *
     * @param string $name
     * @return SilverWareHandler
     */
    public function getHandler($name)
    {
        // Answer Named Handler:
        
        if ($this->hasHandler($name)) {
            return self::$handler_objects[$name];
        }
        
        // Answer Default Handler:
        
        if (isset(self::$handler_default[$this->class])) {
            return self::$handler_default[$this->class];
        }
    }
    
    /**
     * Answers true if a handler exists with the given name.
     *
     * @param string $name
     * @return boolean
     */
    public function hasHandler($name)
    {
        return in_array($name, $this->getHandlerNames());
    }
    
    /**
     * Answers an array of the configured blueprint handler names.
     *
     * @return array
     */
    public function getHandlerNames()
    {
        return array_keys(self::$handler_objects);
    }
    
    /**
     * Changes the class of the given object (if required).
     *
     * @param DataObject $object
     * @return DataObject
     */
    public function mutateClass(DataObject $object)
    {
        if ($ClassName = $this->blueprint->getData('ClassName')) {
            
            if ($object->ClassName != $ClassName) {
                
                // Show Change Message:
                
                $this->changeMessage(
                    sprintf(
                        'Mutating %s (%s to %s)',
                        $this->getIdentifier(),
                        $object->class,
                        $ClassName
                    ),
                    'notice'
                );
                
                // Mutate Object:
                
                $object = $object->newClassInstance($ClassName);
                
                // Write Object to Database:
                
                $object->write();
                $object->flushCache();
                
            }
            
        }
        
        return $object;
    }
    
    /**
     * Populates the given object from fixture data.
     *
     * @param DataObject $object
     * @return DataObject
     */
    public function populate(DataObject $object)
    {
        // Populate Data Fields:
        
        $this->populateData($object);
        
        // Populate Relations:
        
        $this->populateRelations($object);
        
        // Answer Object:
        
        return $object;
    }
    
    /**
     * Populates the defaults for the given object.
     *
     * @param DataObject $object
     * @return DataObject
     */
    public function populateDefaults(DataObject $object)
    {
        // Define Title:
        
        $object->setTitleFromIdentifier($this->getIdentifier());
        
        // Define Defaults (from blueprint):
        
        if ($defaults = $this->blueprint->getDefaults()) {
            
            foreach ($defaults as $name => $value) {
                
                if ($this->blueprint->hasData($name) && $this->blueprint->getData($name) !== false) {
                    continue;
                }
                
                if (is_callable($value)) {
                    
                    $object->$name = $value(
                        $object,
                        $this->blueprint->getData(),
                        $this->blueprint->getFixtures()
                    );
                    
                } else {
                    
                    $object->$name = $value;
                    
                }
                
            }
            
        }
        
        // Answer Object:
        
        return $object;
    }
       
    /**
     * Populates the data for the given object.
     *
     * @param DataObject $object
     * @return DataObject
     */
    public function populateData(DataObject $object)
    {
        foreach ($this->blueprint->getData() as $name => $value) {
            
            if (!$this->isRelation($object, $name) && !$this->hasHandler($name)) {
                $this->setValue($object, $name, $value);
            }
            
        }
        
        return $object;
    }
    
    /**
     * Answers true if the specified field is a relation field of the given object.
     *
     * @param DataObject $object
     * @param string $name
     * @return boolean
     */
    public function isRelation(DataObject $object, $name)
    {
        return (
            $object->manyManyComponent($name) ||
            $object->hasManyComponent($name) ||
            $object->hasOneComponent($name) ||
            $object->hasOneComponent($this->trimID($name))
        );
    }
    
    /**
     * Populates the relations for the given object.
     * 
     * @param DataObject $object
     * @return DataObject
     */
    public function populateRelations(DataObject $object)
    {
        // Iterate Data Fields:
        
        foreach ($this->blueprint->getData() as $name => $value) {
            
            // Handle Has One Relations, Trim Name:
            
            $field = $this->trimID($name);
            
            // Obtain Class Name of Has One Relation:
            
            if ($class = $object->hasOneComponent($field)) {
                
                // Handle Files:
                
                if ($this->isFile($class)) {
                    
                    if (!is_array($value)) {
                        
                        $object->{$field . 'ID'} = $this->processFile(
                            $value,
                            $class,
                            $this->getFolderName($object, $field)
                        );
                        
                    }
                    
                } else {
                    
                    // Validate Reference:
                    
                    $this->checkReference($value);
                    
                    // Create Has One Association:
                    
                    $object->{$field . 'ID'} = $this->processValue($value);
                    
                    // Handle Polymorphic Classes:
                    
                    if ($reference = $this->processReference($value, true)) {
                        
                        if ($class === 'DataObject') {
                            $object->{$field . 'Class'} = $reference['class'];
                        }
                        
                    }
                    
                }
                
            } elseif ($object->hasManyComponent($name) || $object->manyManyComponent($name)) {
                
                // Handle Has Many / Many Many Relations, Write First:
                
                $object->write();
                
                // Process Value:
                
                if (is_array($value)) {
                    
                    foreach ($value as $k => $v) {
                        
                        // Define Extra Fields:
                        
                        $extra = array();
                        
                        // Obtain Item Reference:
                        
                        $item = is_integer($k) ? $v : $k;
                        
                        // Obtain Extra Fields:
                        
                        if (!is_integer($k) && is_array($v)) {
                            $extra = $v;
                        }
                        
                        // Determine Item Type:
                        
                        if ($this->isReference($item)) {
                            
                            // Validate Reference:
                            
                            $this->checkReference($item);
                            
                            // Obtain Reference ID:
                            
                            $id = $this->processValue($item);
                            
                            // Add Many Many Association:
                            
                            if ($object->manyManyComponent($name)) {
                                $object->getManyManyComponents($name)->add($id, $extra);
                            }
                            
                        } else {
                            
                            // Obtain Item Details:
                            
                            $iname = $k;
                            $idata = $v;
                            
                            // Process Item Data:
                            
                            if (is_array($idata)) {
                                
                                // Obtain Item Class:
                                
                                if ($this->isTypedIdentifier($iname)) {
                                    list($iname, $iclass) = $this->processTypedIdentifier($iname);
                                } elseif ($object->isIdentifierMapping($name, $iname)) {
                                    list($iname, $iclass, $edata) = $object->processIdentifierMapping($name, $iname);
                                    $idata += $edata; // combine arrays without replacing existing keys
                                } else {
                                    $iclass = $this->getItemClass($object, $name, $idata);
                                }
                                
                                // Obtain Join Field:
                                
                                $join = $object->getRemoteJoinField($name);
                                
                                // Define Join Field Value:
                                
                                if (!isset($idata[$join])) {
                                    $idata[$join] = $object->ID;
                                }
                                
                                // Define Item Filter:
                                
                                $ifilter = array(
                                    $join => $object->ID
                                );
                                
                                // Create Item Object:
                                
                                $item = $this->getFactory()->createObject($iclass, $iname, $idata, $ifilter);
                                
                            }
                            
                        }
                        
                    }
                    
                } else {
                    
                    // Create ID List:
                    
                    $ids = array();
                    
                    // Process Comma-Delimited String:
                    
                    $items = preg_split('/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
                    
                    // Iterate Split Items:
                    
                    foreach ($items as $item) {
                        
                        // Validate Reference:
                        
                        $this->checkReference($item);
                        
                        // Obtain Reference ID:
                        
                        $ids[] = $this->processValue($item);
                        
                    }
                    
                    // Create Associations by ID List:
                    
                    if ($object->hasManyComponent($name)) {
                        
                        // Define Has Many Association:
                        
                        $object->getComponents($name)->setByIDList($ids);
                        
                    } elseif ($object->manyManyComponent($name)) {
                        
                        // Define Many Many Association:
                        
                        $object->getManyManyComponents($name)->setByIDList($ids);
                        
                    }
                    
                }
                
            }
            
        }
        
        // Finally, Write Object:
        
        if ($object->isChanged(false, DataObject::CHANGE_VALUE)) {
            $object->write();
        }
        
        // Answer Object:
        
        return $object;
    }
    
    /**
     * Shows a database create message for the given object.
     *
     * @param DataObject $object
     */
    public function createMessage(DataObject $object)
    {
        return $this->changeMessage(sprintf('Creating %s (%s)', $this->getIdentifier(), $object->class), 'created');
    }
    
    /**
     * Shows a database update message for the given object.
     *
     * @param DataObject $object
     */
    public function updateMessage(DataObject $object)
    {
        return $this->changeMessage(sprintf('Updating %s (%s)', $this->getIdentifier(), $object->class), 'changed');
    }
    
    /**
     * Shows a database change/alteration message with the specified details.
     *
     * @param string $message
     * @param string $type
     */
    public function changeMessage($message, $type = '')
    {
        return DB::alteration_message($message, $type);
    }
    
    /**
     * Removes the 'ID' from the end of the given field name.
     *
     * @param string $name
     * @return string
     */
    protected function trimID($name)
    {
        return preg_replace('/ID$/', '', $name);
    }
    
    /**
     * Sets the specified value on the given object.
     *
     * @param DataObject $object
     * @param string $name
     * @param string $value
     */
    protected function setValue(DataObject $object, $name, $value)
    {
        $object->$name = $this->processValue($value);
    }
    
    /**
     * Answers the appropriate class name for the given object, field name and item data.
     *
     * @param DataObject $object
     * @param string $field
     * @param array $data
     * @return string
     */
    public function getItemClass(DataObject $object, $field, $data)
    {
        // Initialise:
        
        $defaultClass = "DataObject";
        
        // Obtain Default Class:
        
        if ($class = $object->hasManyComponent($field)) {
            $defaultClass = $class;
        } elseif ($class = $object->manyManyComponent($field)) {
            $defaultClass = $class;
        }
        
        // Answer Class Name:
        
        return isset($data['ClassName']) ? $data['ClassName'] : $defaultClass;
    }
    
    /**
     * Answers true if the given identifier string is a 'typed' identifier (with class name).
     *
     * @param string $identifier
     * @return boolean
     */
    public function isTypedIdentifier($identifier)
    {
        return $this->blueprint->isTypedIdentifier($identifier);
    }
    
    /**
     * Processes the given typed identifier and answers an array containing the true identifier and the class name.
     *
     * @param string $identifier
     * @return array
     */
    public function processTypedIdentifier($identifier)
    {
        return $this->blueprint->processTypedIdentifier($identifier);
    }
    
    /**
     * Answers true if the specified class is a file object.
     *
     * @param string $class
     * @return boolean
     */
    public function isFile($class)
    {
        return $this->blueprint->isFile($class);
    }
    
    /**
     * Answers true if the given value is a fixture reference string.
     *
     * @param string $value
     * @return boolean
     */
    public function isReference($value)
    {
        return $this->blueprint->isReference($value);
    }
    
    /**
     * Checks the validity of the given fixture reference string.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function checkReference($value)
    {
        if (!is_numeric($value) && !preg_match('/^=>[^\.]+\.[^\.]+/', $value)) {
            throw new InvalidArgumentException(sprintf('Invalid format for reference string: %s', $value));
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
        return $this->blueprint->processFile($value, $class, $folder);
    }
    
    /**
     * Processes the given value and answers the resulting data.
     *
     * @param string $value
     * @return mixed
     */
    public function processValue($value)
    {
        return $this->blueprint->processValue($value);
    }
    
    /**
     * Processes the given reference string and returns an array containing the class and identifier.
     *
     * @param string $value
     * @return array
     */
    public function processReference($value)
    {
        return $this->blueprint->processReference($value);
    }
    
    /**
     * Answers the folder name for the given object and field name.
     *
     * @param DataObject $object
     * @param string $field
     * @return string
     */
    protected function getFolderName(DataObject $object, $field)
    {
        // Initialise:
        
        $folder = null;
        
        // Locate File Field:
        
        if ($fileField = $object->getCMSFields()->dataFieldByName($field)) {
            
            if ($fileField instanceof FileField) {
                $folder = $fileField->getFolderName();
            }
            
        }
        
        // Answer Folder Name:
        
        return $folder ? $folder : Config::inst()->get('Upload', 'uploads_folder');
    }
    
    /**
     * Sanitises the given identifier string.
     *
     * @param string $identifier
     * @return string
     */
    protected function cleanIdentifier($identifier)
    {
        return SilverWareIdentifierExtension::clean($identifier);
    }
}
