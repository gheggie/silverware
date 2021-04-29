<?php

/**
 * An extension of the base component class for an area component.
 */
class AreaComponent extends BaseComponent
{
    private static $singular_name = "Area Component";
    private static $plural_name   = "Area Components";
    
    private static $description = "Allows the user to add their own components into an area of the template";
    
    private static $icon = "silverware/images/icons/components/AreaComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        
    );
    
    private static $belongs_many_many = array(
        'Panels' => 'SilverWarePanel'
    );
    
    private static $defaults = array(
        'HideTitle' => 1
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
        
        // Create Field Objects:
        
        if ($toggle = $fields->fieldByName('Root.Style.StyleRulesToggle')) {
            
            $toggle->push(
                DropdownField::create(
                    'ChildRulesID',
                    _t('SilverWareComponent.CHILDCOMPONENTS', 'Child Components'),
                    ComponentStyle::get()->map()
                )->setEmptyString(' ')
            );
            
        }
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers true if the member can create an instance of the receiver.
     *
     * @param Member $member
     * @return boolean
     */
    public function canCreate($member = null)
    {
        // Obtain SiteTree Context:
        
        if (func_num_args() > 1) {
            
            $context = func_get_arg(1);
            
            if (isset($context['Parent'])) {
                
                // Only Allow Creation as child of Grid Column:
                
                return ($context['Parent'] instanceof GridColumn);
                
            }
            
            // Call Parent Method (with context):
            
            return parent::canCreate($member, $context);
        }
        
        // Call Parent Method:
        
        return parent::canCreate($member);
    }
    
    /**
     * Answers a list of the enabled children within the area for the current page.
     *
     * @return ArrayList
     */
    public function getEnabledChildren()
    {
        if (($Page = $this->getCurrentPage()) && $Page instanceof Page) {
            
            if ($Panel = $Page->getPanelForArea($this)) {
                
                return $Panel->getEnabledChildren();
                
            }
            
        }
        
        return ArrayList::create();
    }
    
    /**
     * Answers true if no enabled children are available.
     *
     * @return boolean
     */
    public function Disabled()
    {
        return !$this->getEnabledChildren()->exists();
    }
}

/**
 * An extension of the base component controller class for an area component.
 */
class AreaComponent_Controller extends BaseComponent_Controller
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
