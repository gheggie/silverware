<?php

/**
 * An extension of the base component class for a title component.
 */
class TitleComponent extends BaseComponent
{
    private static $singular_name = "Title Component";
    private static $plural_name   = "Title Components";
    
    private static $description = "A component to show the title of the current page";
    
    private static $icon = "silverware/images/icons/components/TitleComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'HeadingTag' => "Enum('h1, h2, h3, h4, h5, h6', 'h1')"
    );
    
    private static $defaults = array(
        'HideTitle' => 1,
        'HeadingTag' => 'h1'
    );
    
    private static $required_themed_css = array(
        'title-component'
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
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'TitleComponentOptions',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'HeadingTag',
                        _t('TitleComponent.HEADINGTAG', 'Heading tag'),
                        $this->dbObject('HeadingTag')->enumValues()
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
}

/**
 * An extension of the base component controller class for a title component.
 */
class TitleComponent_Controller extends BaseComponent_Controller
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
