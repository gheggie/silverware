<?php

/**
 * An extension of the base component class for a developer component.
 */
class DeveloperComponent extends BaseComponent
{
    private static $singular_name = "Developer Component";
    private static $plural_name   = "Developer Components";
    
    private static $description = "A component for showing attribution to the developer";
    
    private static $icon = "silverware/images/icons/components/DeveloperComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'DeveloperName' => 'Varchar(255)',
        'DeveloperURL' => 'Varchar(2048)',
        'DeveloperText' => 'Varchar(255)',
        'OpenLinkInNewTab' => 'Boolean',
        'DeveloperLinkDisabled' => 'Boolean'
    );
    
    private static $has_one = array(
        'DeveloperPage' => 'SiteTree'
    );
    
    private static $defaults = array(
        'HideTitle' => 1,
        'OpenLinkInNewTab' => 1,
        'DeveloperLinkDisabled' => 0,
        'StyleAlignmentWide' => 'Right',
        'StyleAlignmentNarrow' => 'Center'
    );
    
    private static $extensions = array(
        'StyleAlignmentExtension'
    );
    
    private static $required_themed_css = array(
        'developer-component'
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
                    'DeveloperName',
                    _t('DeveloperComponent.DEVELOPERNAME', 'Developer name')
                ),
                TreeDropdownField::create(
                    'DeveloperPageID',
                    _t('DeveloperComponent.DEVELOPERPAGE', 'Developer Page'),
                    'SiteTree'
                ),
                TextField::create(
                    'DeveloperURL',
                    _t('DeveloperComponent.DEVELOPERURL', 'Developer URL')
                ),
                CheckboxField::create(
                    'OpenLinkInNewTab',
                    _t('DeveloperComponent.OPENLINKINNEWTAB', 'Open link in new tab')
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'DeveloperComponentOptions',
                $this->i18n_singular_name(),
                array(
                    TextField::create(
                        'DeveloperText',
                        _t('DeveloperComponent.DEVELOPERTEXT', 'Developer text')
                    ),
                    CheckboxField::create(
                        'DeveloperLinkDisabled',
                        _t('DeveloperComponent.DEVELOPERLINKDISABLED', 'Developer link disabled')
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
                'DeveloperName',
                'DeveloperText'
            )
        );
    }
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->DeveloperText = _t('DeveloperComponent.DEFAULTDEVELOPERTEXT', 'Developed by {developer}');
    }
    
    /**
     * Answers a string of class names for the wrapper element.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return implode(' ', $this->getWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the wrapper element.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        $classes = array('developer');
        
        return $classes;
    }
    
    /**
     * Answers the appropriate link for the developer page.
     *
     * @return string
     */
    public function getDeveloperLink()
    {
        if ($this->DeveloperURL) {
            return $this->dbObject('DeveloperURL')->URL();
        }
        
        if ($this->DeveloperPageID) {
            return $this->DeveloperPage()->Link();
        }
    }
    
    /**
     * Answers true if the receiver has a developer link.
     *
     * @return boolean
     */
    public function HasDeveloperLink()
    {
        return (boolean) $this->getDeveloperLink();
    }
    
    /**
     * Answers the developer text for the template.
     *
     * @return string
     */
    public function Developer()
    {
        // Create Tokens Array:
        
        $tokens = array();
        
        // Define Developer Token:
        
        if ($this->HasDeveloperLink() && !$this->DeveloperLinkDisabled) {
            
            $href   = $this->DeveloperLink;
            $target = $this->OpenLinkInNewTab ? " target=\"_blank\"" : "";
            
            $tokens['developer'] = sprintf(
                '<a href="%s" rel="nofollow"%s>%s</a>',
                $href,
                $target,
                $this->DeveloperName
            );
            
        } else {
            
            $tokens['developer'] = $this->DeveloperName;
            
        }
        
        // Replace Tokens in Developer Text:
        
        $text = $this->DeveloperText;
        
        foreach ($tokens as $name => $value) {
            $text = str_replace("{{$name}}", $value, $text);
        }
        
        // Answer Developer Text:
        
        return $text;
    }
}

/**
 * An extension of the base component controller class for a developer component.
 */
class DeveloperComponent_Controller extends BaseComponent_Controller
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
