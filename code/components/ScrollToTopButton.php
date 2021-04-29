<?php

/**
 * An extension of the base component class for a scroll to top button.
 */
class ScrollToTopButton extends BaseComponent
{
    private static $singular_name = "Scroll to Top Button";
    private static $plural_name   = "Scroll to Top Buttons";
    
    private static $description = "A button that when clicked or touched scrolls to the top of the page";
    
    private static $icon = "silverware/images/icons/components/ScrollToTopButton.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'Label' => 'Varchar(128)',
        'Rounded' => 'Boolean',
        'ButtonIcon' => 'Varchar(64)',
        'OffsetShow' => 'Int',
        'OffsetOpacity' => 'Int',
        'ScrollDuration' => 'Int',
        'ForegroundColor' => 'Color',
        'BackgroundColor' => 'Color',
        'HoverForegroundColor' => 'Color',
        'HoverBackgroundColor' => 'Color'
    );
    
    private static $defaults = array(
        'Rounded' => 0,
        'ButtonIcon' => 'fa-chevron-up',
        'OffsetShow' => 300,
        'OffsetOpacity' => 1000,
        'ScrollDuration' => 800,
        'ForegroundColor' => 'ffffff',
        'BackgroundColor' => '139fda',
        'HoverForegroundColor' => 'ffffff',
        'HoverBackgroundColor' => '33bffa'
    );
    
    private static $required_themed_css = array(
        'scroll-to-top-button'
    );
    
    private static $required_js_templates = array(
        'silverware/javascript/components/ScrollToTopButton.js'
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
                TextField::create(
                    'Label',
                    _t('ScrollToTopButton.LABEL', 'Label')
                ),
                IconDropdownField::create(
                    'ButtonIcon',
                    _t('ScrollToTopButton.ICON', 'Icon')
                )->setEmptyString(' ')
            )
        );
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            ToggleCompositeField::create(
                'ScrollToTopButtonStyle',
                $this->i18n_singular_name(),
                array(
                    ColorField::create(
                        'ForegroundColor',
                        _t('ScrollToTopButton.FOREGROUNDCOLOR', 'Foreground color')
                    ),
                    ColorField::create(
                        'BackgroundColor',
                        _t('ScrollToTopButton.BACKGROUNDCOLOR', 'Background color')
                    ),
                    ColorField::create(
                        'HoverForegroundColor',
                        _t('ScrollToTopButton.HOVERFOREGROUNDCOLOR', 'Foreground color (hover)')
                    ),
                    ColorField::create(
                        'HoverBackgroundColor',
                        _t('ScrollToTopButton.HOVERBACKGROUNDCOLOR', 'Background color (hover)')
                    ),
                    CheckboxField::create(
                        'Rounded',
                        _t('ScrollToTopButton.ROUNDED', 'Rounded')
                    )
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'ScrollToTopButtonOptions',
                $this->i18n_singular_name(),
                array(
                    NumericField::create(
                        'OffsetShow',
                        _t('ScrollToTopButton.OFFSETSHOW', 'Show offset (in pixels)')
                    )->setRightTitle(
                        _t(
                            'ScrollToTopButton.OFFSETSHOWRIGHTTITLE',
                            'Scroll distance from top before the button shows.'
                        )
                    ),
                    NumericField::create(
                        'OffsetOpacity',
                        _t('ScrollToTopButton.OFFSETOPACITY', 'Opacity offset (in pixels)')
                    )->setRightTitle(
                        _t(
                            'ScrollToTopButton.OFFSETOPACITYRIGHTTITLE',
                            'Scroll distance from top before the button reduces opacity.'
                        )
                    ),
                    NumericField::create(
                        'ScrollDuration',
                        _t('ScrollToTopButton.SCROLLDURATION', 'Scroll duration (in milliseconds)')
                    )->setRightTitle(
                        _t(
                            'ScrollToTopButton.SCROLLDURATIONRIGHTTITLE',
                            'Duration for the scroll to top animation.'
                        )
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->Label = _t('ScrollToTopButton.DEFAULTLABEL', 'Scroll to Top');
    }
    
    /**
     * Answers an array of class names for the receiver.
     *
     * @return array
     */
    public function getClassNames()
    {
        $classes = array(strtolower($this->ClassName));
        
        if ($this->Rounded) {
            $classes[] = "rounded";
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
        return $this->getController()->renderWith('ScrollToTopButton');
    }
    
    /**
     * Answers an array of variables required by the initialisation script.
     *
     * @return array
     */
    public function getJSVars()
    {
        $vars = parent::getJSVars();
        
        $vars = array_merge(
            $vars,
            array(
                'OffsetShow' => $this->OffsetShow,
                'OffsetOpacity' => $this->OffsetOpacity,
                'ScrollDuration' => $this->ScrollDuration
            )
        );
        
        return $vars;
    }
}

/**
 * An extension of the base component controller class for a scroll to top button.
 */
class ScrollToTopButton_Controller extends BaseComponent_Controller
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
