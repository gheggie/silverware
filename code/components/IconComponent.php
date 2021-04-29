<?php

/**
 * An extension of the base component class for an icon component.
 */
class IconComponent extends BaseComponent
{
    private static $singular_name = "Icon Component";
    private static $plural_name   = "Icon Components";
    
    private static $description = "A component to show an icon";
    
    private static $icon = "silverware/images/icons/components/IconComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'IconSize' => 'Int',
        'IconUnit' => "Enum('px, em, rem, pt, cm, in', 'px')",
        'IconSizeNarrow' => 'Decimal(3,2,0.75)'
    );
    
    private static $defaults = array(
        'HideTitle' => 1,
        'IconSize' => 128,
        'IconUnit' => 'px',
        'IconSizeNarrow' => 0.75
    );
    
    private static $extensions = array(
        'StyleAlignmentExtension',
        'SilverWareFontIconExtension'
    );
    
    private static $required_themed_css = array(
        'icon-component'
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
        
        // Modify Icon Fields:
        
        $fields->removeByName('FontIconSize');
        
        // Create Icon Fields:
        
        $fields->insertAfter(
            'FontIcon',
            FieldGroup::create(
                _t('IconComponent.ICONSIZE', 'Icon size'),
                array(
                    NumericField::create('IconSize', '')->setAttribute(
                        'placeholder',
                        _t('IconComponent.SIZE', 'Size')
                    )->setMaxLength(5),
                    DropdownField::create(
                        'IconUnit',
                        '',
                        $this->dbObject('IconUnit')->enumValues()
                    )
                )
            )
        );
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'IconComponentStyle',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'IconSizeNarrow',
                        _t('IconComponent.ICONSIZENARROW', 'Icon size (narrow)'),
                        SilverWareTools::percentage_options(100, 50, 5)
                    )->setRightTitle(
                        _t(
                            'IconComponent.ICONSIZENARROWRIGHTTITLE',
                            'Reduces the icon size by the specified factor for narrow devices.'
                        )
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
                'FontIcon'
            )
        );
    }
    
    /**
     * Answers a string of class names for the icon wrapper element.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return implode(' ', $this->getWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the icon wrapper element.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        $classes = array('icon');
        
        $classes[] = strtolower($this->getStyleAlignmentWideClass());
        
        $classes[] = strtolower($this->getStyleAlignmentNarrowClass());
        
        $classes[] = strtolower($this->getStyleAlignmentVerticalClass());
        
        return $classes;
    }
    
    /**
     * Answers the CSS string for the icon font size style.
     *
     * @return string
     */
    public function getIconSizeCSS()
    {
        return $this->IconSize . $this->IconUnit;
    }
    
     /**
     * Answers the CSS string for the narrow icon font size style.
     *
     * @return string
     */
    public function getIconSizeNarrowCSS()
    {
        return round(($this->IconSize * $this->IconSizeNarrow), 2) . $this->IconUnit;
    }
}

/**
 * An extension of the base component controller class for an icon component.
 */
class IconComponent_Controller extends BaseComponent_Controller
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
