<?php

/**
 * An extension of the SilverWare folder class for a SilverWare panel folder.
 */
class SilverWarePanelFolder extends SilverWareFolder
{
    private static $singular_name = "Panel Folder";
    private static $plural_name   = "Panel Folders";
    
    private static $description = "Holds a series of SilverWare panels";
    
    private static $icon = "silverware/images/icons/SilverWarePanelFolder.png";
    
    private static $hide_ancestor = "SilverWareFolder";
    
    private static $default_child = "SilverWarePanel";
    
    private static $allowed_children = array(
        'SilverWarePanel'
    );
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->Title = _t('SilverWarePanelFolder.DEFAULTTITLE', 'Panels');
    }
}

/**
 * An extension of the SilverWare folder controller class for a SilverWare panel folder.
 */
class SilverWarePanelFolder_Controller extends SilverWareFolder_Controller
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
