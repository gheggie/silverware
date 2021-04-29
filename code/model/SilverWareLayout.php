<?php

/**
 * An extension of the SilverWare section holder class for a SilverWare layout.
 */
class SilverWareLayout extends SilverWareSectionHolder
{
    private static $singular_name = "Layout";
    private static $plural_name   = "Layouts";
    
    private static $description = "An individual SilverWare layout";
    
    private static $icon = "silverware/images/icons/SilverWareLayout.png";
    
    private static $hide_ancestor = "SilverWareSectionHolder";
    
    private static $default_identifier = "main";
    
    /**
     * Answers true if the default record for the receiver has been created.
     *
     * @return boolean
     */
    public static function has_default()
    {
        return self::get()->filter('Identifier', self::$default_identifier)->exists();
    }
    
    /**
     * Answers true if the receiver contains a PageComponent as a descendant (required for rendering layout).
     *
     * @return boolean
     */
    public function hasPageComponent()
    {
        // Iterate Descendants:
        
        foreach (SiteTree::get()->filter('ID', $this->getDescendantIDList()) as $node) {
            
            // Answer True (if found):
            
            if ($node instanceof PageComponent) {
                return true;
            }
            
        }
        
        // Answer False (if not found):
        
        return false;
    }
}

/**
 * An extension of the SilverWare section holder controller class for a SilverWare layout.
 */
class SilverWareLayout_Controller extends SilverWareSectionHolder_Controller
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
