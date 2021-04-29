<?php

/**
 * An extension of the SilverWare section class for a header section.
 */
class HeaderSection extends SilverWareSection
{
    private static $singular_name = "Header Section";
    private static $plural_name   = "Header Sections";
    
    private static $description = "A header section within a SilverWare template";
    
    private static $icon = "silverware/images/icons/sections/HeaderSection.png";
    
    private static $db = array(
        'Sticky' => 'Boolean',
        'StickyScrollDistance' => 'Int'
    );
    
    private static $defaults = array(
        'Sticky' => 0,
        'StickyScrollDistance' => 100
    );
    
    private static $required_themed_css = array(
        'header-section'
    );
    
    private static $required_js_templates = array(
        'silverware/javascript/components/HeaderSection.js'
    );
    
    protected $tag = "header";
    
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
                'HeaderSectionOptions',
                $this->i18n_singular_name(),
                array(
                    CheckboxField::create(
                        'Sticky',
                        _t('HeaderSection.STICKY', 'Sticky')
                    ),
                    NumericField::create(
                        'StickyScrollDistance',
                        _t('HeaderSection.STICKYSCROLLDISTANCE', 'Sticky scroll distance (in pixels)')
                    )->setRightTitle(
                        _t(
                            'HeaderSection.STICKYSCROLLDISTANCERIGHTTITLE',
                            'Scroll distance from the top of the page when header becomes sticky.'
                        )
                    )->displayIf('Sticky')->isChecked()->end()
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
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
                'StickyScrollDistance' => $this->StickyScrollDistance
            )
        );
        
        return $vars;
    }
    
    /**
     * Answers true if the required JavaScript is disabled.
     *
     * @return boolean
     */
    public function getRequiredJSDisabled()
    {
        return !$this->Sticky;
    }
}

/**
 * An extension of the SilverWare section controller class for a header section.
 */
class HeaderSection_Controller extends SilverWareSection_Controller
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
