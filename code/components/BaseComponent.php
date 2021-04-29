<?php

/**
 * An extension of the SilverWare component class for a base component.
 */
class BaseComponent extends SilverWareComponent
{
    private static $singular_name = "Base Component";
    private static $plural_name   = "Base Components";
    
    private static $description = "A base component within a SilverWare template or layout";
    
    private static $hide_ancestor = "SilverWareComponent";
    
    private static $db = array(
        'Disabled' => 'Boolean',
        'HideTitle' => 'Boolean',
        'TitleAlignmentWide' => "Enum('Left, Center, Right, Justify', 'Left')",
        'TitleAlignmentNarrow' => "Enum('Left, Center, Right, Justify', 'Left')"
    );
    
    private static $defaults = array(
        'Disabled' => 0,
        'HideTitle' => 0,
        'TitleAlignmentWide' => 'Left',
        'TitleAlignmentNarrow' => 'Left'
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
        
        // Create Style Fields:
        
        $fields->addFieldsToTab(
            'Root.Style',
            array(
                ToggleCompositeField::create(
                    'StyleTitleToggle',
                    _t('BaseComponent.TITLE', 'Title'),
                    array(
                        DropdownField::create(
                            'TitleAlignmentWide',
                            _t('BaseComponent.TITLEALIGNMENTWIDE', 'Title alignment (wide)'),
                            $this->dbObject('TitleAlignmentWide')->enumValues()
                        ),
                        DropdownField::create(
                            'TitleAlignmentNarrow',
                            _t('BaseComponent.TITLEALIGNMENTNARROW', 'Title alignment (narrow)'),
                            $this->dbObject('TitleAlignmentNarrow')->enumValues()
                        )
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldsToTab(
            'Root.Options',
            array(
                ToggleCompositeField::create(
                    'TitleOptions',
                    _t('BaseComponent.TITLE', 'Title'),
                    array(
                        CheckboxField::create(
                            'HideTitle',
                            _t('BaseComponent.HIDETITLE', 'Hide title')
                        )
                    )
                ),
                ToggleCompositeField::create(
                    'StatusOptions',
                    _t('BaseComponent.STATUS', 'Status'),
                    array(
                        CheckboxField::create(
                            'Disabled',
                            _t('BaseComponent.DISABLED', 'Disabled')
                        )
                    )
                )
            )
        );
        
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
        
        $classes[] = "typography";
        
        return $classes;
    }
    
    /**
     * Answers a string of class names for the component title.
     *
     * @return string
     */
    public function getTitleClass()
    {
        return implode(' ', $this->getTitleClassNames());
    }
    
    /**
     * Answers an array of class names for the component title.
     *
     * @return array
     */
    public function getTitleClassNames()
    {
        return array(
            $this->getTitleAlignmentWideClass(),
            $this->getTitleAlignmentNarrowClass()
        );
    }
    
    /**
     * Answers the title alignment class name for wide devices.
     *
     * @return string
     */
    public function getTitleAlignmentWideClass()
    {
        return strtolower('wide-' . $this->TitleAlignmentWide);
    }
    
    /**
     * Answers the title alignment class name for narrow devices.
     *
     * @return string
     */
    public function getTitleAlignmentNarrowClass()
    {
        return strtolower('narrow-' . $this->TitleAlignmentNarrow);
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
        
        if ($this->getField('Disabled')) {
            $classes .= " component-disabled";
        }
        
        return $classes;
    }
    
    /**
     * Renders the receiver using the appropriate template file.
     *
     * @param string $layout
     * @param string $title
     * @return HTMLText
     */
    public function RenderTemplate($layout = null, $title = null)
    {
        return $this->getController()->customise(
            array(
                'Content' => $this->Content($layout, $title)
            )
        )->renderWith('BaseComponent');
    }
    
    /**
    * Answers the content for the HTML template.
    *
    * @param string $layout
    * @param string $title
    * @return HTMLText
    */
    public function Content($layout = null, $title = null)
    {
        return $this->getController()->customise(
            array(
                'Title' => $title,
                'Layout' => $layout,
                'Component' => $this
            )
        )->renderWith($this->ClassName);
    }
    
    /**
     * Answers true if the receiver is disabled within the template.
     *
     * @return boolean
     */
    public function Disabled()
    {
        return $this->getField('Disabled');
    }
    
    /**
     * Answers true if the component title is to be shown in the template.
     *
     * @return boolean
     */
    public function ShowTitle()
    {
        return !$this->HideTitle;
    }
}

/**
 * An extension of the SilverWare component controller class for a base component.
 */
class BaseComponent_Controller extends SilverWareComponent_Controller
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
