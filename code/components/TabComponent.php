<?php

/**
 * An extension of the base component class for a tab component.
 */
class TabComponent extends BaseComponent
{
    private static $singular_name = "Tab Component";
    private static $plural_name   = "Tab Components";
    
    private static $description = "A component for showing content in a series of tabs";
    
    private static $icon = "silverware/images/icons/components/TabComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'Animation' => "Enum('None, Fade, Slide', 'None')",
        'AnimationDuration' => 'Int',
        'Rotate' => "Enum('No, Yes', 'No')",
        'RotateDelay' => 'Int',
        'Collapsible' => "Enum('No, Yes, Tabs, Accordion', 'No')",
        'StartCollapsed' => "Enum('No, Yes, Tabs, Accordion', 'No')",
        'DefaultTabBackgroundColor' => 'Color',
        'DefaultTabForegroundColor' => 'Color',
        'ActiveTabBackgroundColor' => 'Color',
        'ActiveTabForegroundColor' => 'Color',
        'PanelBackgroundColor' => 'Color',
        'TabsBackgroundColor' => 'Color',
        'BackgroundColor' => 'Color',
        'BorderColor' => 'Color',
        'BorderWidth' => 'Int',
        'BorderStyle' => "Enum('none, solid, dashed, dotted, double', 'solid')",
        'TabMarginRight' => 'Int',
        'AccordionWidth' => 'Int',
        'ReferenceTabInURL' => 'Boolean',
        'ScrollToAccordion' => 'Boolean'
    );
    
    private static $has_many = array(
        'Tabs' => 'SilverWareTab'
    );
    
    private static $defaults = array(
        'Animation' => 'None',
        'AnimationDuration' => 500,
        'Rotate' => 'No',
        'RotateDelay' => 5000,
        'Collapsible' => 'No',
        'StartCollapsed' => 'No',
        'DefaultTabBackgroundColor' => '139fda',
        'DefaultTabForegroundColor' => 'ffffff',
        'ActiveTabBackgroundColor' => 'ffffff',
        'ActiveTabForegroundColor' => '139fda',
        'PanelBackgroundColor' => 'ffffff',
        'TabsBackgroundColor' => '139fda',
        'BackgroundColor' => '139fda',
        'BorderColor' => '139fda',
        'BorderStyle' => 'solid',
        'BorderWidth' => 1,
        'TabMarginRight' => 0,
        'ReferenceTabInURL' => 0,
        'ScrollToAccordion' => 0,
        'AccordionWidth' => 500
    );
    
    private static $required_themed_css = array(
        'tab-component',
        'tab-component.theme'
    );
    
    private static $required_js = array(
        'silverware/thirdparty/responsive-tabs/responsive-tabs.min.js'
    );
    
    private static $required_js_templates = array(
        'silverware/javascript/responsive-tabs/responsive-tabs.init.js'
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
        
        // Insert Tabs Tab:
        
        $fields->insertAfter(
            Tab::create(
                'Tabs',
                _t('TabComponent.TABS', 'Tabs')
            ),
            'Main'
        );
        
        // Add Links Grid Field to Tab:
        
        $fields->addFieldToTab(
            'Root.Tabs',
            GridField::create(
                'Tabs',
                _t('TabComponent.TABS', 'Tabs'),
                $this->Tabs(),
                $config = GridFieldConfig_OrderableEditor::create()
            )
        );
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'TabComponentStyle',
                $this->i18n_singular_name(),
                array(
                    ColorField::create(
                        'BackgroundColor',
                        _t('TabComponent.BACKGROUNDCOLOR', 'Background color')
                    ),
                    ColorField::create(
                        'TabsBackgroundColor',
                        _t('TabComponent.TABSBACKGROUNDCOLOR', 'Tabs background color')
                    ),
                    ColorField::create(
                        'PanelBackgroundColor',
                        _t('TabComponent.PANELBACKGROUNDCOLOR', 'Panel background color')
                    ),
                    ColorField::create(
                        'DefaultTabForegroundColor',
                        _t('TabComponent.DEFAULTTABFOREGROUNDCOLOR', 'Default tab foreground color')
                    ),
                    ColorField::create(
                        'DefaultTabBackgroundColor',
                        _t('TabComponent.DEFAULTTABBACKGROUNDCOLOR', 'Default tab background color')
                    ),
                    ColorField::create(
                        'ActiveTabForegroundColor',
                        _t('TabComponent.ACTIVETABFOREGROUNDCOLOR', 'Active tab foreground color')
                    ),
                    ColorField::create(
                        'ActiveTabBackgroundColor',
                        _t('TabComponent.ACTIVETABBACKGROUNDCOLOR', 'Active tab background color')
                    ),
                    ColorField::create(
                        'BorderColor',
                        _t('TabComponent.BORDERCOLOR', 'Border color')
                    ),
                    DropdownField::create(
                        'BorderStyle',
                        _t('TabComponent.BORDERSTYLE', 'Border style'),
                        $this->dbObject('BorderStyle')->enumValues()
                    ),
                    NumericField::create(
                        'BorderWidth',
                        _t('TabComponent.BORDERWIDTH', 'Border width (in pixels)')
                    ),
                    NumericField::create(
                        'TabMarginRight',
                        _t('TabComponent.TABMARGINRIGHT', 'Tab margin right (in pixels)')
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'TabComponentOptions',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'Collapsible',
                        _t('TabComponent.COLLAPSIBLE', 'Collapsible'),
                        $this->dbObject('Collapsible')->enumValues()
                    ),
                    DropdownField::create(
                        'StartCollapsed',
                        _t('TabComponent.STARTCOLLAPSED', 'Start collapsed'),
                        $this->dbObject('StartCollapsed')->enumValues()
                    ),
                    DropdownField::create(
                        'Rotate',
                        _t('TabComponent.ROTATE', 'Rotate'),
                        $this->dbObject('Rotate')->enumValues()
                    ),
                    NumericField::create(
                        'RotateDelay',
                        _t('TabComponent.ROTATEDELAY', 'Rotate delay (in milliseconds)')
                    ),
                    DropdownField::create(
                        'Animation',
                        _t('TabComponent.ANIMATION', 'Animation'),
                        $this->dbObject('Animation')->enumValues()
                    ),
                    NumericField::create(
                        'AnimationDuration',
                        _t('TabComponent.ANIMATIONDURATION', 'Animation duration (in milliseconds)')
                    ),
                    NumericField::create(
                        'AccordionWidth',
                        _t('TabComponent.ACCORDIONWIDTH', 'Accordion width (in pixels)')
                    )->setRightTitle(
                        _t(
                            'TabComponent.ACCORDIONWIDTHRIGHTTITLE',
                            'Specifies the container width when the component changes from tabs to accordion mode.'
                        )
                    ),
                    CheckboxField::create(
                        'ScrollToAccordion',
                        _t('TabComponent.SCROLLTOACCORDION', 'Scroll to opened accordion panel')
                    ),
                    CheckboxField::create(
                        'ReferenceTabInURL',
                        _t('TabComponent.REFERENCETABINURL', 'Reference tab in URL of page')
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
     * Answers a unique ID for the wrapper element.
     *
     * @return string
     */
    public function getWrapperID()
    {
        return $this->getHTMLID() . "_Wrapper";
    }
    
    /**
     * Answers the rotate type for the initialisation script.
     *
     * @return string|integer
     */
    public function getRotateType()
    {
        if ($this->Rotate != 'No') {
            return $this->RotateDelay;
        }
        
        return 'false';
    }
    
    /**
     * Answers the animation method for the initialisation script.
     *
     * @return string
     */
    public function getAnimationMethod()
    {
        if ($this->Animation != 'None') {
            return "'" . strtolower($this->Animation) . "'";
        }
        
        return 'false';
    }
    
    /**
     * Answers the collapsible type for the initialisation script.
     *
     * @return string
     */
    public function getCollapsibleType()
    {
        if ($this->Collapsible == 'No') {
            return 'false';
        } elseif ($this->Collapsible == 'Yes') {
            return 'true';
        } else {
            return "'" . strtolower($this->Collapsible) . "'";
        }
    }
    
    /**
     * Answers the start collapsed type for the initialisation script.
     *
     * @return string
     */
    public function getStartCollapsedType()
    {
        if ($this->StartCollapsed == 'No') {
            return 'false';
        } elseif ($this->StartCollapsed == 'Yes') {
            return 'true';
        } else {
            return "'" . strtolower($this->StartCollapsed) . "'";
        }
    }
    
    /**
     * Answers the inactive tabs for the initialisation script.
     *
     * @return string
     */
    public function getInactiveTabs()
    {
        $tabs = array();
        
        foreach ($this->EnabledTabs() as $index => $tab) {
            
            if ($tab->Inactive) {
                
                $tabs[] = $index;
                
            }
            
        }
        
        return '[' . implode(', ', $tabs) . ']';
    }
    
    /**
     * Answers the index of the first active tab.
     *
     * @return integer
     */
    public function getFirstActiveTabIndex()
    {
        foreach ($this->EnabledTabs() as $index => $tab) {
            
            if (!$tab->Inactive) {
                
                return $index;
                
            }
            
        }
        
        return 0;
    }
    
    /**
     * Answers an array of variables required by the initialisation script.
     *
     * @return array
     */
    public function getJSVars()
    {
        $vars = parent::getJSVars();
        
        $vars['WrapperID'] = $this->getWrapperID();
        
        $vars['AnimationMethod'] = $this->AnimationMethod;
        $vars['AnimationDuration'] = $this->AnimationDuration;
        $vars['CollapsibleType'] = $this->CollapsibleType;
        $vars['StartCollapsedType'] = $this->StartCollapsedType;
        $vars['RotateType'] = $this->RotateType;
        $vars['ReferenceTabInURL'] = $this->dbObject('ReferenceTabInURL')->NiceAsBoolean();
        $vars['ScrollToAccordion'] = $this->dbObject('ScrollToAccordion')->NiceAsBoolean();
        $vars['InactiveTabs'] = $this->InactiveTabs;
        $vars['FirstActiveTabIndex'] = $this->FirstActiveTabIndex;
        
        return $vars;
    }
    
    /**
     * Answers a data list which contains only the tabs which are not disabled.
     *
     * @return DataList
     */
    public function EnabledTabs()
    {
        // Disable Anchor Rewriting (breaks tabs JavaScript if enabled):
        
        Config::inst()->update('SSViewer', 'rewrite_hash_links', false);
        
        // Answer Enabled Tabs:
        
        return $this->Tabs()->filter(array('Disabled' => 0));
    }
}

/**
 * An extension of the base component controller class for a tab component.
 */
class TabComponent_Controller extends BaseComponent_Controller
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
