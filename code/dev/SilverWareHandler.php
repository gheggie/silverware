<?php

/**
 * An extension of the object class for the abstract parent class of SilverWare handler implementations.
 */
abstract class SilverWareHandler extends Object
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct($name = null)
    {
        // Construct Parent:
        
        parent::__construct();
        
        // Construct Object:
        
        if ($name) {
            $this->setName($name);
        } elseif (!$this->name) {
            $this->setName($this->getNameFromClass());
        }
    }
    
    /**
     * Handles the given blueprint name and data for the provided object.
     *
     * @param SilverWareCreator $creator
     * @param DataObject $object
     * @param string $name
     * @param array $data
     * @return boolean
     */
    abstract function handle(SilverWareCreator $creator, DataObject $object, $name, $data = null);
    
    /**
     * Answers true if the receiver handles the specified name.
     *
     * @return boolean
     */
    public function handles($name)
    {
        return ($this->name == $name);
    }
    
    /**
     * Defines the value of the name attribute.
     *
     * @param string $name
     * @return SilverWareHandler
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Answers the value of the name attribute.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Answers the default name for the receiver by extracting it from the class name.
     *
     * @return string
     */
    public function getNameFromClass()
    {
        if ($i = strpos($this->class, 'Handler')) {
            return substr($this->class, 0, $i);
        }
    }
}
