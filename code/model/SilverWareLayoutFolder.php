<?php

/**
 * An extension of the SilverWare folder class for a SilverWare layout folder.
 */
class SilverWareLayoutFolder extends SilverWareFolder
{
    private static $singular_name = "Layout Folder";
    private static $plural_name   = "Layout Folders";
    
    private static $description = "Holds a series of SilverWare layouts";
    
    private static $icon = "silverware/images/icons/SilverWareLayoutFolder.png";
    
    private static $hide_ancestor = "SilverWareFolder";
    
    private static $default_child = "SilverWareLayout";
    
    private static $allowed_children = array(
        'SilverWareLayout'
    );
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->Title = _t('SilverWareLayoutFolder.DEFAULTTITLE', 'Layouts');
    }
}

/**
 * An extension of the SilverWare folder controller class for a SilverWare layout folder.
 */
class SilverWareLayoutFolder_Controller extends SilverWareFolder_Controller
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
