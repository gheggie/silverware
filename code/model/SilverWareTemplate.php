<?php

/**
 * An extension of the SilverWare section holder class for a SilverWare template.
 */
class SilverWareTemplate extends SilverWareSectionHolder
{
    private static $singular_name = "Template";
    private static $plural_name   = "Templates";
    
    private static $description = "An individual SilverWare template";
    
    private static $icon = "silverware/images/icons/SilverWareTemplate.png";
    
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
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers an array of class names for the receiver.
     *
     * @return array
     */
    public function getClassNames()
    {
        $classes = parent::getClassNames();
        
        if ($Page = $this->getCurrentPage()) {
            
            $classes[] = $Page->ClassName;
            
        }
        
        return $classes;
    }
}

/**
 * An extension of the SilverWare section holder controller class for a SilverWare template.
 */
class SilverWareTemplate_Controller extends SilverWareSectionHolder_Controller
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
