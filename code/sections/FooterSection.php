<?php

/**
 * An extension of the SilverWare section class for a footer section.
 */
class FooterSection extends SilverWareSection
{
    private static $singular_name = "Footer Section";
    private static $plural_name   = "Footer Sections";
    
    private static $description = "A footer section within a SilverWare template";
    
    private static $icon = "silverware/images/icons/sections/FooterSection.png";
    
    protected $tag = "footer";
}

/**
 * An extension of the SilverWare section controller class for a footer section.
 */
class FooterSection_Controller extends SilverWareSection_Controller
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
