<?php

/**
 * An extension of the SilverWare folder class for a SilverWare template folder.
 */
class SilverWareTemplateFolder extends SilverWareFolder
{
    private static $singular_name = "Template Folder";
    private static $plural_name   = "Template Folders";
    
    private static $description = "Holds a series of SilverWare templates";
    
    private static $icon = "silverware/images/icons/SilverWareTemplateFolder.png";
    
    private static $hide_ancestor = "SilverWareFolder";
    
    private static $default_child = "SilverWareTemplate";
    
    private static $allowed_children = array(
        'SilverWareTemplate'
    );
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->Title = _t('SilverWareTemplateFolder.DEFAULTTITLE', 'Templates');
    }
}

/**
 * An extension of the SilverWare folder controller class for a SilverWare template folder.
 */
class SilverWareTemplateFolder_Controller extends SilverWareFolder_Controller
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
