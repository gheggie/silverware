<?php

/**
 * An extension of the base component class for a page component.
 */
class PageComponent extends BaseComponent
{
    private static $singular_name = "Page Component";
    private static $plural_name   = "Page Components";
    
    private static $description = "A component to render the current page within a SilverWare layout";
    
    private static $icon = "silverware/images/icons/components/PageComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'HidePageTitle' => 'Boolean'
    );
    
    private static $defaults = array(
        'HideTitle' => 1,
        'HidePageTitle' => 0
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
        
        // Create Options Fields:
        
        $fields->fieldByName('Root.Options.TitleOptions')->push(
            CheckboxField::create(
                'HidePageTitle',
                _t('PageComponent.HIDEPAGETITLE', 'Hide title of page')
            )
        );
        
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
     * Disables the receiver if the current controller does not require a page component.
     *
     * @return boolean
     */
    public function Disabled()
    {
        return (($controller = $this->getCurrentController()) && $controller->DisablePageComponent);
    }
}

/**
 * An extension of the base component controller class for a page component.
 */
class PageComponent_Controller extends BaseComponent_Controller
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
