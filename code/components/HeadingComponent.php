<?php

/**
 * An extension of the base component class for a heading component.
 */
class HeadingComponent extends BaseComponent
{
    private static $singular_name = "Heading Component";
    private static $plural_name   = "Heading Components";
    
    private static $description = "A component to show a heading";
    
    private static $icon = "silverware/images/icons/components/HeadingComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'HeadingTag' => "Enum('h1, h2, h3, h4, h5, h6', 'h4')"
    );
    
    private static $defaults = array(
        'HideTitle' => 1,
        'HeadingTag' => 'h4'
    );
    
    private static $extensions = array(
        'StyleAlignmentExtension',
        'SilverWareFontIconExtension'
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
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'HeadingComponentOptions',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'HeadingTag',
                        _t('HeadingComponent.HEADINGTAG', 'Heading tag'),
                        $this->dbObject('HeadingTag')->enumValues()
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create(
            array(
                
            )
        );
    }
    
    /**
     * Answers a string of CSS classes to apply to the receiver in the CMS tree.
     *
     * @param string $numChildrenMethod
     * @return string
     */
    public function CMSTreeClasses($numChildrenMethod = 'numChildren')
    {
        $classes = parent::CMSTreeClasses($numChildrenMethod);
        
        $classes .= " heading-" . $this->HeadingTag;
        
        return $classes;
    }
}

/**
 * An extension of the base component controller class for a heading component.
 */
class HeadingComponent_Controller extends BaseComponent_Controller
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
