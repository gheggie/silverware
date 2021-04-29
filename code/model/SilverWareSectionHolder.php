<?php

/**
 * An extension of the SilverWare component class for a SilverWare section holder.
 */
class SilverWareSectionHolder extends SilverWareComponent
{
    private static $singular_name = "Section Holder";
    private static $plural_name   = "Section Holders";
    
    private static $description = "Abstract parent class of objects which hold a series of sections";
    
    private static $can_be_root = false;
    
    private static $default_child = "SilverWareSection";
    
    private static $defaults = array(
        'ShowInMenus' => 0,
        'ShowInSearch' => 0
    );
    
    private static $allowed_children = array(
        'SilverWareSection'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Remove Field Objects:
        
        $fields->removeFieldsFromTab('Root.Main', array('Content', 'Metadata'));
        
        // Define Field Title:
        
        $title = $this->i18n_singular_name() . ' ' . strtolower(_t('SilverWareSectionHolder.NAME', 'name'));
        
        // Update Field Objects:
        
        $fields->dataFieldByName('Title')->setTitle($title);
        $fields->dataFieldByName('MenuTitle')->addExtraClass('hidden');
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers the enabled sections within the receiver.
     *
     * @return DataList
     */
    public function Sections()
    {
        return $this->AllChildren();
    }
    
    /**
     * Answers an array of custom CSS required for the template.
     *
     * @return array
     */
    public function getCustomCSS()
    {
        // Obtain CSS Array:
        
        $css = parent::getCustomCSS();
        
        // Merge CSS from All Components:
        
        foreach ($this->getEnabledComponents() as $Component) {
            
            $css = array_merge($css, $Component->getCustomCSS());
            
        }
        
        // Filter CSS Array:
        
        $css = array_filter($css);
        
        // Answer CSS Array:
        
        return $css;
    }
}

/**
 * An extension of the SilverWare component controller class for a SilverWare section holder.
 */
class SilverWareSectionHolder_Controller extends SilverWareComponent_Controller
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
