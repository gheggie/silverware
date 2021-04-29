<?php

/**
 * An extension of the grid field config record editor class for orderable multi class editor config.
 */
class GridFieldConfig_MultiClassEditor extends GridFieldConfig_RecordEditor
{
    /**
     * Constructs the object upon instantiation.
     *
     * @param integer $itemsPerPage
     */
    public function __construct($itemsPerPage = null)
    {
        // Construct Parent:
        
        parent::__construct($itemsPerPage);
        
        // Construct Object:
        
        $this->addComponents(
            new GridFieldOrderableRows(),
            new GridFieldAddNewMultiClass()
        )->removeComponentsByType('GridFieldAddNewButton');
        
        // Apply Extensions:
        
        $this->extend('updateConfig');
    }
    
    /**
     * Defines the classes that can be created using the grid field.
     *
     * @param array $classes Class names optionally mapped to titles
     * @param string $default Optional default class name
     * @return GridFieldConfig
     */
    public function setClasses($classes, $default = null)
    {
        if ($Component = $this->getComponentByType('GridFieldAddNewMultiClass')) {
            
            $Component->setClasses($classes, $default);
            
        }
        
        return $this;
    }
    
    /**
     * Defines the creatable classes as descendants of the specified class.
     *
     * @param string $class Parent class name
     * @param string $default Optional default class name
     * @return GridFieldConfig
     */
    public function useDescendantsOf($class, $default = null)
    {
        return $this->setClasses(SilverWareTools::descendants_of($class), $default);
    }
    
    /**
     * Defines the creatable classes as subclasses of the specified class (includes the specified class).
     *
     * @param string $class Parent class name
     * @param string $default Optional default class name
     * @return GridFieldConfig
     */
    public function useSubclassesOf($class, $default = null)
    {
        return $this->setClasses(array_keys(ClassInfo::subclassesFor($class)), $default);
    }
}
