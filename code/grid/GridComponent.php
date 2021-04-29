<?php

/**
 * An extension of the SilverWare component class for a grid component.
 */
class GridComponent extends SilverWareComponent
{
    private static $singular_name = "Grid Component";
    private static $plural_name   = "Grid Components";
    
    private static $description = "Abstract parent class of objects which maintain a grid structure";
    
    /**
     * Converts the given string width to a CSS class.
     *
     * @param string $string
     * @return string
     */
    protected function getStringWidthClass($string)
    {
        return SilverWareTools::nice_column_to_css($string);
    }
    
    /**
     * Converts the given string offset to a CSS class.
     *
     * @param string $string
     * @return string
     */
    protected function getStringOffsetClass($string)
    {
        return SilverWareTools::nice_offset_to_css($string);
    }
    
    /**
     * Converts the given integer width to a CSS class.
     *
     * @param integer $integer
     * @return string
     */
    protected function getIntegerWidthClass($integer)
    {
        return SilverWareTools::int_column_to_css($integer);
    }
}

/**
 * An extension of the SilverWare component controller class for a grid component.
 */
class GridComponent_Controller extends SilverWareComponent_Controller
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
