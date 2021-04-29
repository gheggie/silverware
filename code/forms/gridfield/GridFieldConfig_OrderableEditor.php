<?php

/**
 * An extension of the grid field config record editor class for orderable editor config.
 */
class GridFieldConfig_OrderableEditor extends GridFieldConfig_RecordEditor
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
        
        $this->addComponent(new GridFieldOrderableRows());
        
        // Apply Extensions:
        
        $this->extend('updateConfig');
    }
}
