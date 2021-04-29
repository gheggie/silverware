<?php

/**
 * An extension of the base component class for a button component.
 */
class ButtonComponent extends BaseComponent
{
    private static $singular_name = "Button Component";
    private static $plural_name   = "Button Components";
    
    private static $description = "A component to show one or more button links";
    
    private static $icon = "silverware/images/icons/components/ButtonComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $has_many = array(
        'Buttons' => 'SilverWareButton'
    );
    
    private static $defaults = array(
        'HideTitle' => 1,
        'StyleAlignmentWide' => 'Center',
        'StyleAlignmentNarrow' => 'Center',
        'StyleAlignmentVertical' => 'Baseline'
    );
    
    private static $extensions = array(
        'StyleAlignmentExtension'
    );
    
    private static $required_themed_css = array(
        'button-component'
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
        
        // Insert Buttons Tab:
        
        $fields->insertAfter(
            Tab::create(
                'Buttons',
                _t('ButtonComponent.BUTTONS', 'Buttons')
            ),
            'Main'
        );
        
        // Add Buttons Grid Field to Tab:
        
        $fields->addFieldToTab(
            'Root.Buttons',
            GridField::create(
                'Buttons',
                _t('ButtonComponent.BUTTONS', 'Buttons'),
                $this->Buttons(),
                $config = GridFieldConfig_OrderableEditor::create()
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a string of class names for the list element.
     *
     * @return string
     */
    public function getListClass()
    {
        return implode(' ', $this->getListClassNames());
    }
    
    /**
     * Answers an array of class names for the list element.
     *
     * @return array
     */
    public function getListClassNames()
    {
        $classes = array('buttons');
        
        return $classes;
    }
    
    /**
     * Answers a list of enabled buttons for the template.
     *
     * @return DataList
     */
    public function EnabledButtons()
    {
        return $this->Buttons()->filter(array('Disabled' => 0));
    }
}

/**
 * An extension of the base component controller class for a button component.
 */
class ButtonComponent_Controller extends BaseComponent_Controller
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
